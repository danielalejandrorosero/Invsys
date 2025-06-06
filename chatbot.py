import os
import json
import logging
from typing import List, Dict, Any
from datetime import datetime
import mysql.connector
from mysql.connector import Error
from dotenv import load_dotenv
import requests
from dataclasses import dataclass
from pathlib import Path
import re
import time

# Configurar logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

@dataclass
class Message:
    role: str
    content: str

class InventoryChatbot:
    def __init__(self):
        # Cargar variables de entorno
        load_dotenv(Path(__file__).parent / 'config' / '.env')
        
        # Configurar conexión a la base de datos
        self.db_config = {
            'host': os.getenv('DB_SERVER'),
            'user': os.getenv('DB_USERNAME'),
            'password': os.getenv('DB_PASSWORD'),
            'database': os.getenv('DB_DATABASE')
        }
        
        # Inicializar historial de conversación
        self.conversation_history: List[Message] = []
        
        # Prompt del sistema
        self.system_prompt = """
        Eres un asistente especializado en gestión de inventario para el sistema IMS_invsys. 
        Tu objetivo es ayudar a los usuarios a gestionar eficientemente su inventario respondiendo 
        a sus consultas de manera directa y en lenguaje natural.

        REGLAS IMPORTANTES:
        1. Responde de manera directa y clara
        2. NO pidas información adicional a menos que sea absolutamente necesario
        3. Si el usuario saluda, responde con un saludo amigable y ofrece ayuda
        4. NO menciones SQL ni detalles técnicos
        5. NO pidas confirmaciones innecesarias
        6. NO asumas qué preguntas hará el usuario
        7. Responde de forma natural y conversacional

        ESTRUCTURA DE LA BASE DE DATOS:
        - productos: id, nombre, descripcion, precio_compra, precio_venta, stock_minimo, stock_maximo, categoria_id, unidad_medida_id
        - categorias: id, nombre, descripcion
        - stock_almacen: id, producto_id, almacen_id, cantidad
        - almacenes: id, nombre, ubicacion
        - unidades_medida: id, nombre, simbolo
        - alertas_stock: id, producto_id, tipo_alerta, mensaje, fecha
        - movimientos_stock: id, producto_id, almacen_id, tipo_movimiento, cantidad, fecha
        - proveedores: id, nombre, contacto, telefono, email
        - compras: id, proveedor_id, fecha, total
        - detalle_compras: id, compra_id, producto_id, cantidad, precio_unitario
        - ventas: id, cliente_id, fecha, total
        - detalle_ventas: id, venta_id, producto_id, cantidad, precio_unitario

        FORMATO DE RESPUESTA:
        Responde siempre en lenguaje natural, sin mencionar detalles técnicos.
        """

    def connect_db(self):
        """Establece conexión con la base de datos"""
        try:
            # Verificar que las variables de entorno estén cargadas
            if not all([self.db_config['host'], self.db_config['user'], 
                       self.db_config['password'], self.db_config['database']]):
                logger.error("Faltan variables de entorno para la conexión a la base de datos")
                logger.error(f"Configuración actual: {self.db_config}")
                raise Exception("Faltan variables de entorno para la conexión a la base de datos")
            
            # Intentar conexión con retry
            max_retries = 3
            retry_count = 0
            last_error = None
            
            while retry_count < max_retries:
                try:
                    conn = mysql.connector.connect(
                        host=self.db_config['host'],
                        user=self.db_config['user'],
                        password=self.db_config['password'],
                        database=self.db_config['database'],
                        connect_timeout=5,  # Timeout de 5 segundos
                        auth_plugin='mysql_native_password'  # Forzar método de autenticación
                    )
                    # Verificar que la conexión está activa
                    if conn.is_connected():
                        logger.info("Conexión exitosa a la base de datos")
                        return conn
                except Error as e:
                    last_error = e
                    retry_count += 1
                    logger.warning(f"Intento {retry_count} de conexión fallido: {e}")
                    if retry_count < max_retries:
                        time.sleep(1)  # Esperar 1 segundo antes de reintentar
            
            # Si llegamos aquí, todos los intentos fallaron
            logger.error(f"Todos los intentos de conexión fallaron. Último error: {last_error}")
            raise last_error
            
        except Error as e:
            logger.error(f"Error conectando a la base de datos: {e}")
            if e.errno == 1045:  # Error de acceso
                raise Exception("Error de acceso a la base de datos. Verifica las credenciales.")
            elif e.errno == 2003:  # No se puede conectar al servidor
                raise Exception("No se puede conectar al servidor de la base de datos.")
            else:
                raise Exception(f"Error de conexión a la base de datos: {str(e)}")
        except Exception as e:
            logger.error(f"Error inesperado al conectar: {e}")
            raise Exception(f"Error al conectar con la base de datos: {str(e)}")

    def get_gemini_response(self, message: str, context: str) -> str:
        """Obtiene respuesta del modelo Gemini"""
        try:
            url = f'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={os.getenv("GOOGLE_API_KEY")}'
            
            data = {
                'contents': [{
                    'parts': [{
                        'text': f"{context}\n\nUsuario: {message}"
                    }]
                }],
                'generationConfig': {
                    'temperature': 0.3,  # Reducido para respuestas más precisas
                    'maxOutputTokens': 2048,
                    'topP': 0.95
                }
            }

            response = requests.post(url, json=data)
            response.raise_for_status()
            
            response_data = response.json()
            return response_data['candidates'][0]['content']['parts'][0]['text']
            
        except Exception as e:
            logger.error(f"Error en API de Gemini: {e}")
            raise

    def process_response(self, response: str) -> str:
        """Procesa la respuesta del modelo y ejecuta consultas SQL si es necesario"""
        try:
            # Si es un saludo, responder directamente
            if any(greeting in response.lower() for greeting in ['hola', 'buenos días', 'buenas tardes', 'buenas noches']):
                return "¡Hola! Soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?"

            # Si pregunta por el número de productos
            if any(phrase in response.lower() for phrase in ['cuantos productos', 'cuántos productos', 'total de productos', 'productos en total']):
                try:
                    with self.connect_db() as conn:
                        with conn.cursor(dictionary=True) as cursor:
                            # Obtener el total de productos
                            cursor.execute("""
                                SELECT COUNT(*) as total 
                                FROM productos 
                                WHERE estado = 'activo'
                            """)
                            result = cursor.fetchone()
                            
                            # Obtener la fecha de la última actualización
                            cursor.execute("""
                                SELECT MAX(fecha_actualizacion) as ultima_actualizacion
                                FROM productos
                                WHERE estado = 'activo'
                            """)
                            fecha = cursor.fetchone()
                            
                            if result:
                                timestamp = fecha['ultima_actualizacion'].strftime('%d/%m/%Y %H:%M:%S') if fecha and fecha['ultima_actualizacion'] else 'desconocida'
                                return f"Actualmente tienes {result['total']} productos activos en tu inventario (última actualización: {timestamp})."
                except Exception as e:
                    logger.error(f"Error en consulta de productos: {e}")
                    return f"Lo siento, no pude obtener el número de productos. Error: {str(e)}"

            # Si pregunta por productos con bajo stock
            if any(phrase in response.lower() for phrase in ['bajo stock', 'bajos en stock', 'stock bajo', 'productos con bajo stock']):
                try:
                    with self.connect_db() as conn:
                        with conn.cursor(dictionary=True) as cursor:
                            cursor.execute("""
                                SELECT 
                                    productos.nombre,
                                    stock_almacen.cantidad,
                                    productos.stock_minimo,
                                    almacenes.nombre as almacen,
                                    unidades_medida.simbolo as unidad,
                                    productos.fecha_actualizacion
                                FROM productos
                                JOIN stock_almacen ON productos.id = stock_almacen.producto_id
                                JOIN almacenes ON stock_almacen.almacen_id = almacenes.id
                                JOIN unidades_medida ON productos.unidad_medida_id = unidades_medida.id
                                WHERE stock_almacen.cantidad <= productos.stock_minimo
                                AND productos.estado = 'activo'
                                ORDER BY stock_almacen.cantidad ASC
                            """)
                            results = cursor.fetchall()
                            if results:
                                response = "Los siguientes productos están bajo su stock mínimo:\n"
                                for row in results:
                                    fecha = row['fecha_actualizacion'].strftime('%d/%m/%Y %H:%M:%S') if row['fecha_actualizacion'] else 'desconocida'
                                    response += f"- {row['nombre']}: {row['cantidad']} {row['unidad']} (mínimo: {row['stock_minimo']} {row['unidad']}) en almacén {row['almacen']} (última actualización: {fecha})\n"
                                return response
                            else:
                                return "No hay productos bajo su stock mínimo en este momento."
                except Exception as e:
                    logger.error(f"Error en consulta de stock bajo: {e}")
                    return f"Lo siento, no pude obtener la información de stock bajo. Error: {str(e)}"

            # Si no se detectó un patrón específico, devolver la respuesta del modelo
            return response

        except Exception as e:
            logger.error(f"Error procesando respuesta: {e}")
            return f"Lo siento, hubo un error al procesar tu solicitud. Error: {str(e)}"

    def handle_request(self, message: str) -> str:
        """Maneja una solicitud del usuario"""
        try:
            # Agregar mensaje al historial
            self.conversation_history.append(Message("user", message))
            
            # Si es un saludo, responder directamente
            if message.lower() in ['hola', 'buenos días', 'buenas tardes', 'buenas noches']:
                response = "¡Hola! Soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?"
                self.conversation_history.append(Message("assistant", response))
                return response
            
            # Construir contexto
            context = self.system_prompt + "\n\nHistorial de la conversación:\n"
            for msg in self.conversation_history[-5:]:  # Solo últimos 5 mensajes para contexto
                context += f"{msg.role}: {msg.content}\n"
            
            # Obtener y procesar respuesta
            ai_response = self.get_gemini_response(message, context)
            processed_response = self.process_response(ai_response)
            
            # Si la respuesta es un saludo, intentar obtener una respuesta más específica
            if processed_response.lower().startswith('¡hola!'):
                processed_response = self.process_response(message)
            
            # Agregar respuesta al historial
            self.conversation_history.append(Message("assistant", processed_response))
            
            # Registrar interacción
            self.log_interaction(message, processed_response)
            
            return processed_response
            
        except Exception as e:
            logger.error(f"Error procesando solicitud: {e}")
            return "Lo siento, hubo un error al procesar tu solicitud. Por favor, intenta nuevamente."

    def log_interaction(self, user_input: str, bot_response: str):
        """Registra la interacción en la base de datos"""
        try:
            with self.connect_db() as conn:
                with conn.cursor() as cursor:
                    sql = """
                    INSERT INTO chat_logs (user_input, bot_response, timestamp)
                    VALUES (%s, %s, %s)
                    """
                    cursor.execute(sql, (user_input, bot_response, datetime.now()))
                    conn.commit()
        except Exception as e:
            logger.error(f"Error registrando interacción: {e}")

# Ejemplo de uso
if __name__ == "__main__":
    chatbot = InventoryChatbot()
    
    # Ejemplo de interacción
    while True:
        user_input = input("Tú: ")
        if user_input.lower() in ['salir', 'exit', 'quit']:
            break
            
        response = chatbot.handle_request(user_input)
        print(f"Bot: {response}") 