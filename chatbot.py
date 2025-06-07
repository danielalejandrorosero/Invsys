import os
import json
import logging
from typing import List, Dict
from datetime import datetime
import mysql.connector
from mysql.connector import Error
from dotenv import load_dotenv
import requests
from dataclasses import dataclass
from pathlib import Path

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
        Eres un asistente especializado en consultas de inventario para el sistema IMS_invsys.
        Tu objetivo es ayudar a los usuarios a obtener información del inventario mediante consultas SELECT.

        REGLAS ESTRICTAS:
        1. SOLO puedes realizar consultas SELECT
        2. NO puedes modificar, insertar o eliminar datos
        3. Responde de manera directa y clara
        4. NO pidas información adicional innecesaria
        5. Responde en lenguaje natural y conversacional
        6. NO menciones detalles técnicos ni SQL
        7. SIEMPRE ejecuta la consulta más relevante para la pregunta
        8. NO preguntes por más detalles a menos que sea ABSOLUTAMENTE necesario
        9. SIEMPRE asuma el caso más común si no se especifica lo contrario

        INSTRUCCIONES PARA CONSULTAS:
        Cuando el usuario pregunte por información, DEBES responder con la consulta SQL exacta entre etiquetas ```sql```.
        Por ejemplo, si preguntan "cuántos productos hay", debes responder:
        ```sql
        SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'
        ```

        CONSULTAS PREDEFINIDAS:
        - Para "cuántos productos": 
        ```sql
        SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'
        ```
        - Para "buscar producto por código o SKU": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE (p.codigo = '[CODIGO]' OR p.sku = '[SKU]') AND p.estado = 'activo'
        ```
        - Para "productos por categoría": 
        ```sql
        SELECT c.nombre as categoria, COUNT(p.id_producto) as total_productos
        FROM categorias c
        LEFT JOIN productos p ON c.id_categoria = p.id_categoria
        WHERE p.estado = 'activo'
        GROUP BY c.id_categoria, c.nombre
        ```
        - Para "listar productos de una categoría": 
        ```sql
        SELECT p.nombre, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo'
        AND c.nombre = '[NOMBRE_CATEGORIA]'
        ```
        - Para "productos por proveedor": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.id_proveedor = [ID_PROVEEDOR] AND p.estado = 'activo'
        ```
        - Para "productos por unidad de medida": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.id_unidad_medida = [ID_UNIDAD] AND p.estado = 'activo'
        ```
        - Para "productos con stock bajo": 
        ```sql
        SELECT p.id_producto, p.nombre, sa.cantidad_disponible, p.stock_minimo
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        WHERE sa.cantidad_disponible <= p.stock_minimo
        AND p.estado = 'activo'
        ```
        - Para "productos con stock alto": 
        ```sql
        SELECT p.id_producto, p.nombre, sa.cantidad_disponible, p.stock_maximo
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        WHERE sa.cantidad_disponible > p.stock_maximo
        AND p.estado = 'activo'
        ```
        - Para "productos sin stock": 
        ```sql
        SELECT p.id_producto, p.nombre, COALESCE(sa.cantidad_disponible, 0) as stock_actual
        FROM productos p
        LEFT JOIN stock_almacen sa ON p.id_producto = sa.id_producto
        WHERE (sa.cantidad_disponible IS NULL OR sa.cantidad_disponible = 0)
        AND p.estado = 'activo'
        ```
        - Para "productos más caros": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        ORDER BY p.precio_venta DESC 
        LIMIT 10
        ```
        - Para "productos más baratos": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        ORDER BY p.precio_venta ASC 
        LIMIT 10
        ```
        - Para "productos recientes": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        ORDER BY p.fecha_creacion DESC 
        LIMIT 10
        ```
        - Para "productos actualizados": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        ORDER BY p.fecha_actualizacion DESC 
        LIMIT 10
        ```
        - Para "productos con margen alto": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida,
        ((p.precio_venta - p.precio_compra) / p.precio_compra * 100) as margen 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        ORDER BY margen DESC 
        LIMIT 10
        ```
        - Para "productos eliminados": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'eliminado'
        ```
        - Para "productos con imágenes": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida,
        COALESCE(ip.nombre_imagen, 'default.png') as imagen_destacada 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        LEFT JOIN imagenes_productos ip ON p.id_producto = ip.id_producto 
        WHERE p.estado = 'activo'
        ```
        - Para "productos por rango de precios": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        AND p.precio_venta BETWEEN [PRECIO_MIN] AND [PRECIO_MAX]
        ```
        - Para "productos por descripción": 
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.estado = 'activo' 
        AND p.descripcion LIKE '%[TEXTO]%'
        ```
        - Para "listar almacenes": 
        ```sql
        SELECT id_almacen, nombre, ubicacion
        FROM almacenes
        ```
        - Para "stock por almacén": 
        ```sql
        SELECT a.nombre as almacen, 
        COUNT(DISTINCT sa.id_producto) as total_productos,
        COALESCE(SUM(sa.cantidad_disponible), 0) as stock_total
        FROM almacenes a
        LEFT JOIN stock_almacen sa ON a.id_almacen = sa.id_almacen
        GROUP BY a.id_almacen, a.nombre
        ```
        - Para "productos por almacén": 
        ```sql
        SELECT p.nombre, a.nombre as almacen, 
        COALESCE(sa.cantidad_disponible, 0) as stock_actual
        FROM productos p
        JOIN stock_almacen sa ON p.id_producto = sa.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        WHERE p.estado = 'activo'
        AND a.nombre = '[NOMBRE_ALMACEN]'
        ```
        - Para "contar transferencias": 
        ```sql
        SELECT COUNT(*) as total_transferencias
        FROM transferencias
        ```
        - Para "transferencias por almacén": 
        ```sql
        SELECT 
            a_origen.nombre as almacen_origen,
            a_destino.nombre as almacen_destino,
            COUNT(*) as total_transferencias
        FROM transferencias t
        JOIN almacenes a_origen ON t.id_almacen_origen = a_origen.id_almacen
        JOIN almacenes a_destino ON t.id_almacen_destino = a_destino.id_almacen
        GROUP BY t.id_almacen_origen, t.id_almacen_destino
        ```

        - para saber cuantos almacenes hay y cuantos productos hay en cada uno
        ```sql
        SELECT a.nombre as almacen, COUNT(sa.id_producto) as total_productos
        FROM almacenes a
        LEFT JOIN stock_almacen sa ON a.id_almacen = sa.id_almacen
        GROUP BY a.id_almacen, a.nombre
        ```

        - para productos por categoria
        ```sql
        SELECT p.*, c.nombre as categoria, pr.nombre as proveedor, um.nombre as unidad_medida 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor 
        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad 
        WHERE p.id_categoria = [ID_CATEGORIA] AND p.estado = 'activo'
        ```
        - Para "alertas de stock": 
        ```sql
        SELECT p.nombre, sa.cantidad_disponible, p.stock_minimo, a.nombre as almacen
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        WHERE sa.cantidad_disponible <= p.stock_minimo
        AND p.estado = 'activo'
        ```
        - Para "movimientos recientes": 
        ```sql
        SELECT p.nombre, ms.tipo_movimiento, ms.cantidad, ms.fecha_movimiento
        FROM movimientos_stock ms
        JOIN productos p ON ms.id_producto = p.id_producto
        ORDER BY ms.fecha_movimiento DESC
        LIMIT 10
        ```
        - Para "stock por almacén": 
        ```sql
        SELECT a.nombre as almacen, 
        COUNT(DISTINCT sa.id_producto) as total_productos,
        COALESCE(SUM(sa.cantidad_disponible), 0) as stock_total
        FROM almacenes a
        LEFT JOIN stock_almacen sa ON a.id_almacen = sa.id_almacen
        GROUP BY a.id_almacen, a.nombre
        ```
        - Para "productos por almacén": 
        ```sql
        SELECT p.nombre, a.nombre as almacen, 
        COALESCE(sa.cantidad_disponible, 0) as stock_actual
        FROM productos p
        JOIN stock_almacen sa ON p.id_producto = sa.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        WHERE p.estado = 'activo'
        AND a.nombre = '[NOMBRE_ALMACEN]'
        ```
        - Para "listar almacenes": 
        ```sql
        SELECT id_almacen, nombre, ubicacion
        FROM almacenes
        ```
        - Para "productos con stock alto": 
        ```sql
        SELECT p.nombre, sa.cantidad_disponible, p.stock_maximo, a.nombre as almacen
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        WHERE sa.cantidad_disponible > p.stock_maximo
        AND p.estado = 'activo'
        ```
        - Para "productos sin stock": 
        ```sql
        SELECT p.nombre, COALESCE(sa.cantidad_disponible, 0) as stock_actual
        FROM productos p
        LEFT JOIN stock_almacen sa ON p.id_producto = sa.id_producto
        WHERE (sa.cantidad_disponible IS NULL OR sa.cantidad_disponible = 0)
        AND p.estado = 'activo'
        ```
        - Para "valor del inventario": 
        ```sql
        SELECT SUM(sa.cantidad_disponible * p.precio_compra) as valor_total
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        ```
        - Para "productos por proveedor": 
        ```sql
        SELECT pr.nombre as proveedor, COUNT(p.id_producto) as total_productos
        FROM proveedores pr
        LEFT JOIN productos p ON pr.id_proveedor = p.id_proveedor
        WHERE p.estado = 'activo'
        GROUP BY pr.id_proveedor, pr.nombre
        ```
        - Para "contar almacenes": 
        ```sql
        SELECT COUNT(id_almacen) as total
        FROM almacenes
        ```
        - Para "productos por almacén específico": 
        ```sql
        SELECT p.nombre, sa.cantidad_disponible
        FROM productos p
        JOIN stock_almacen sa ON p.id_producto = sa.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        WHERE a.nombre = '[NOMBRE_ALMACEN]'
        AND p.estado = 'activo'
        ```

        ESTRUCTURA COMPLETA DE LA BASE DE DATOS:

        TABLA: productos
        - id: Identificador único
        - nombre: Nombre del producto
        - descripcion: Descripción detallada
        - precio_compra: Precio de compra
        - precio_venta: Precio de venta
        - stock_minimo: Stock mínimo permitido
        - stock_maximo: Stock máximo permitido
        - categoria_id: ID de la categoría
        - unidad_medida_id: ID de la unidad de medida
        - estado: Estado del producto (activo/inactivo)
        - fecha_creacion: Fecha de creación
        - fecha_actualizacion: Fecha de última actualización

        TABLA: categorias
        - id: Identificador único
        - nombre: Nombre de la categoría
        - descripcion: Descripción de la categoría
        - estado: Estado de la categoría

        TABLA: stock_almacen
        - id: Identificador único
        - producto_id: ID del producto
        - almacen_id: ID del almacén
        - cantidad: Cantidad en stock
        - fecha_actualizacion: Fecha de última actualización

        TABLA: almacenes
        - id: Identificador único
        - nombre: Nombre del almacén
        - ubicacion: Ubicación física
        - estado: Estado del almacén

        TABLA: unidades_medida
        - id: Identificador único
        - nombre: Nombre de la unidad
        - simbolo: Símbolo de la unidad
        - estado: Estado de la unidad

        TABLA: alertas_stock
        - id: Identificador único
        - producto_id: ID del producto
        - tipo_alerta: Tipo de alerta
        - mensaje: Mensaje de la alerta
        - fecha: Fecha de la alerta
        - estado: Estado de la alerta

        TABLA: movimientos_stock
        - id: Identificador único
        - producto_id: ID del producto
        - almacen_id: ID del almacén
        - tipo_movimiento: Tipo de movimiento
        - cantidad: Cantidad movida
        - fecha: Fecha del movimiento
        - usuario_id: ID del usuario que realizó el movimiento

        TABLA: proveedores
        - id: Identificador único
        - nombre: Nombre del proveedor
        - contacto: Nombre del contacto
        - telefono: Teléfono de contacto
        - email: Correo electrónico
        - estado: Estado del proveedor

        TABLA: compras
        - id: Identificador único
        - proveedor_id: ID del proveedor
        - fecha: Fecha de la compra
        - total: Monto total
        - estado: Estado de la compra
        - usuario_id: ID del usuario que realizó la compra

        TABLA: detalle_compras
        - id: Identificador único
        - compra_id: ID de la compra
        - producto_id: ID del producto
        - cantidad: Cantidad comprada
        - precio_unitario: Precio por unidad

        TABLA: ventas
        - id: Identificador único
        - cliente_id: ID del cliente
        - fecha: Fecha de la venta
        - total: Monto total
        - estado: Estado de la venta
        - usuario_id: ID del usuario que realizó la venta

        TABLA: detalle_ventas
        - id: Identificador único
        - venta_id: ID de la venta
        - producto_id: ID del producto
        - cantidad: Cantidad vendida
        - precio_unitario: Precio por unidad

        TABLA: clientes
        - id: Identificador único
        - nombre: Nombre del cliente
        - contacto: Nombre del contacto
        - telefono: Teléfono de contacto
        - email: Correo electrónico
        - estado: Estado del cliente

        TABLA: usuarios
        - id: Identificador único
        - nombre: Nombre del usuario
        - email: Correo electrónico
        - password: Contraseña (hasheada)
        - rol: Rol del usuario
        - estado: Estado del usuario

        TABLA: grupos
        - id: Identificador único
        - nombre: Nombre del grupo
        - descripcion: Descripción del grupo
        - estado: Estado del grupo

        TABLA: imagenes_productos
        - id: Identificador único
        - producto_id: ID del producto
        - ruta: Ruta de la imagen
        - tipo: Tipo de imagen
        - estado: Estado de la imagen

        TABLA: imagenes_usuarios
        - id: Identificador único
        - usuario_id: ID del usuario
        - ruta: Ruta de la imagen
        - tipo: Tipo de imagen
        - estado: Estado de la imagen

        TABLA: chat_logs
        - id: Identificador único
        - user_input: Entrada del usuario
        - bot_response: Respuesta del bot
        - timestamp: Fecha y hora de la interacción

        RELACIONES IMPORTANTES:
        - productos.categoria_id -> categorias.id
        - productos.unidad_medida_id -> unidades_medida.id
        - stock_almacen.producto_id -> productos.id
        - stock_almacen.almacen_id -> almacenes.id
        - alertas_stock.producto_id -> productos.id
        - movimientos_stock.producto_id -> productos.id
        - movimientos_stock.almacen_id -> almacenes.id
        - compras.proveedor_id -> proveedores.id
        - detalle_compras.compra_id -> compras.id
        - detalle_compras.producto_id -> productos.id
        - ventas.cliente_id -> clientes.id
        - detalle_ventas.venta_id -> ventas.id
        - detalle_ventas.producto_id -> productos.id
        - imagenes_productos.producto_id -> productos.id
        - imagenes_usuarios.usuario_id -> usuarios.id

        EJEMPLOS DE CONSULTAS COMUNES:
        1. Total de productos activos
        2. Stock por almacén
        3. Productos con stock bajo
        4. Movimientos recientes
        5. Ventas por período
        6. Compras por proveedor
        7. Productos por categoría
        8. Clientes activos
        9. Proveedores activos
        10. Alertas de stock

        FORMATO DE RESPUESTA:
        1. SIEMPRE responde con la consulta SQL exacta entre etiquetas ```sql```
        2. NO preguntes por más detalles
        3. Usa las consultas predefinidas cuando corresponda
        4. NO incluyas texto adicional antes o después de la consulta SQL
        """

    def connect_db(self):
        """Establece conexión con la base de datos"""
        try:
            return mysql.connector.connect(**self.db_config)
        except Error as e:
            logger.error(f"Error conectando a la base de datos: {e}")
            raise

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
                    'temperature': 0.3,
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

    def sanitize_sql_query(self, query: str) -> bool:
        """Verifica si una consulta SQL es segura"""
        # Lista de palabras clave peligrosas
        dangerous_keywords = [
            'DROP', 'DELETE', 'UPDATE', 'INSERT', 'ALTER', 'TRUNCATE',
            'EXEC', 'EXECUTE', 'UNION', '--', ';', '/*', '*/', 'WAITFOR',
            'DELAY', 'SLEEP', 'BENCHMARK', 'LOAD_FILE', 'INTO OUTFILE',
            'INTO DUMPFILE', 'SHUTDOWN', 'KILL', 'GRANT', 'REVOKE'
        ]
        
        # Convertir a mayúsculas para la comparación
        query_upper = query.upper()
        
        # Verificar palabras clave peligrosas
        for keyword in dangerous_keywords:
            if keyword in query_upper:
                logger.warning(f"Intento de inyección SQL detectado: {query}")
                return False
                
        # Verificar que solo sea una consulta SELECT
        if not query_upper.strip().startswith('SELECT'):
            logger.warning(f"Intento de ejecutar consulta no SELECT: {query}")
            return False
            
        return True

    def execute_select_query(self, query: str) -> List[Dict]:
        """Ejecuta una consulta SELECT de manera segura"""
        try:
            # Verificar si la consulta es segura
            if not self.sanitize_sql_query(query):
                raise ValueError("Consulta SQL no permitida por razones de seguridad")
            
            # Ejecutar la consulta
            cursor = self.conn.cursor(dictionary=True)
            cursor.execute(query)
            results = cursor.fetchall()
            cursor.close()
            
            return results
            
        except Exception as e:
            logger.error(f"Error ejecutando consulta SQL: {e}")
            raise

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
            
            # Si el mensaje es muy corto o no tiene sentido, pedir aclaración
            if len(message.strip()) < 2:
                response = "Lo siento, no entiendo tu pregunta. ¿Podrías ser más específico?"
                self.conversation_history.append(Message("assistant", response))
                return response
                
            # Verificar si el mensaje contiene intentos de inyección SQL
            if any(keyword in message.upper() for keyword in ['SELECT', 'FROM', 'WHERE', 'DROP', 'DELETE', 'UPDATE', 'INSERT']):
                response = "Lo siento, no puedo procesar consultas SQL directamente. Por favor, formula tu pregunta en lenguaje natural."
                self.conversation_history.append(Message("assistant", response))
                return response
            
            # Construir contexto
            context = self.system_prompt + "\n\nHistorial de la conversación:\n"
            for msg in self.conversation_history[-5:]:
                context += f"{msg.role}: {msg.content}\n"
            
            # Obtener respuesta del modelo
            response = self.get_gemini_response(message, context)
            
            # Si la respuesta contiene una consulta SQL, ejecutarla de manera segura
            if "SELECT" in response.upper():
                try:
                    # Extraer la consulta SQL
                    sql_query = response.split("```sql")[1].split("```")[0].strip()
                    
                    # Verificar si la consulta es segura
                    if not self.sanitize_sql_query(sql_query):
                        response = "Lo siento, no puedo ejecutar esa consulta por razones de seguridad."
                    else:
                        results = self.execute_select_query(sql_query)
                        
                        # Formatear los resultados
                        if "COUNT" in sql_query.upper():
                            # Para consultas de conteo
                            total = results[0]['total'] if results else 0
                            response = f"📊 Tienes {total} productos activos en el inventario."
                        else:
                            # Para otras consultas
                            formatted_response = "📊 Resultados de la consulta:\n\n"
                            for row in results:
                                formatted_response += " | ".join(f"{k}: {v}" for k, v in row.items()) + "\n"
                            response = formatted_response
                except Exception as e:
                    response = f"❌ Error al ejecutar la consulta: {str(e)}"
            
            # Agregar respuesta al historial
            self.conversation_history.append(Message("assistant", response))
            
            # Registrar interacción
            self.log_interaction(message, response)
            
            return response
            
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

    def procesar_consulta(self, consulta):
        # Convertir la consulta a minúsculas para hacer la búsqueda insensible a mayúsculas
        consulta = consulta.lower()
        
        # Buscar la consulta SQL correspondiente
        consulta_sql = None
        for key, value in self.consultas.items():
            if key.lower() in consulta:
                consulta_sql = value
                break
        
        if consulta_sql:
            try:
                # Ejecutar la consulta
                self.cursor.execute(consulta_sql)
                resultados = self.cursor.fetchall()
                
                # Formatear la respuesta de manera amigable
                if "contar almacenes" in consulta.lower():
                    return f"📊 Tienes {resultados[0][0]} almacenes en el sistema."
                
                elif "listar almacenes" in consulta.lower():
                    almacenes = [f"{row[0]} ({row[1]})" for row in resultados]
                    return f"📊 Almacenes disponibles:\n" + "\n".join(almacenes)
                
                elif "contar productos" in consulta.lower():
                    return f"📊 Tienes {resultados[0][0]} productos activos en el inventario."
                
                elif "productos por almacén" in consulta.lower():
                    if not resultados:
                        return "📊 No hay productos en este almacén."
                    productos = [f"{row[0]}: {row[1]} unidades" for row in resultados]
                    return f"📊 Productos en el almacén:\n" + "\n".join(productos)
                
                elif "alertas de stock" in consulta.lower():
                    if not resultados:
                        return "📊 No hay alertas de stock en este momento."
                    alertas = [f"{row[0]}: {row[1]} unidades (mínimo: {row[2]}) en {row[3]}" for row in resultados]
                    return f"📊 Alertas de stock:\n" + "\n".join(alertas)
                
                elif "productos sin stock" in consulta.lower():
                    if not resultados:
                        return "📊 No hay productos sin stock."
                    productos = [f"{row[0]}" for row in resultados]
                    return f"📊 Productos sin stock:\n" + "\n".join(productos)
                
                elif "productos con stock alto" in consulta.lower():
                    if not resultados:
                        return "📊 No hay productos con stock alto."
                    productos = [f"{row[0]}: {row[1]} unidades (máximo: {row[2]}) en {row[3]}" for row in resultados]
                    return f"📊 Productos con stock alto:\n" + "\n".join(productos)
                
                elif "productos por categoría" in consulta.lower():
                    if not resultados:
                        return "📊 No hay productos por categoría."
                    categorias = [f"{row[0]}: {row[1]} productos" for row in resultados]
                    return f"📊 Productos por categoría:\n" + "\n".join(categorias)
                
                elif "movimientos recientes" in consulta.lower():
                    if not resultados:
                        return "📊 No hay movimientos recientes."
                    movimientos = [f"{row[0]}: {row[1]} - {row[2]} unidades ({row[3]})" for row in resultados]
                    return f"📊 Movimientos recientes:\n" + "\n".join(movimientos)
                
                elif "valor del inventario" in consulta.lower():
                    return f"📊 El valor total del inventario es: ${resultados[0][0]:,.2f}"
                
                elif "productos por proveedor" in consulta.lower():
                    if not resultados:
                        return "📊 No hay productos por proveedor."
                    proveedores = [f"{row[0]}: {row[1]} productos" for row in resultados]
                    return f"📊 Productos por proveedor:\n" + "\n".join(proveedores)
                
                else:
                    # Para cualquier otra consulta, mostrar los resultados de forma genérica
                    if not resultados:
                        return "📊 No se encontraron resultados."
                    return "📊 Resultados de la consulta:\n" + "\n".join([str(row) for row in resultados])
                
            except Exception as e:
                return f"❌ Error al ejecutar la consulta: {str(e)}"
        else:
            return "Lo siento, no entiendo tu pregunta. ¿Podrías reformularla?"

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