import os
import json
import logging
import re
from typing import List, Dict, Optional
from datetime import datetime
import mysql.connector
from mysql.connector import Error
from dotenv import load_dotenv
import requests
from dataclasses import dataclass
from pathlib import Path

# Configurar logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

@dataclass
class Message:
    role: str
    content: str
    timestamp: datetime = None
    
    def __post_init__(self):
        if self.timestamp is None:
            self.timestamp = datetime.now()

class DatabaseManager:
    """Maneja las conexiones y operaciones de base de datos"""
    
    def __init__(self, db_config: Dict):
        self.db_config = db_config
        self.conn = None
        self._connect()
    
    def _connect(self):
        """Establece conexión con la base de datos"""
        try:
            self.conn = mysql.connector.connect(**self.db_config)
            logger.info("Conexión a base de datos establecida")
        except Error as e:
            logger.error(f"Error conectando a la base de datos: {e}")
            self.conn = None
    
    def reconnect(self):
        """Reintenta la conexión a la base de datos"""
        if self.conn:
            try:
                self.conn.close()
            except:
                pass
        self._connect()
    
    def is_connected(self) -> bool:
        """Verifica si hay conexión activa"""
        try:
            return self.conn and self.conn.is_connected()
        except:
            return False
    
    def execute_query(self, query: str) -> List[Dict]:
        """Ejecuta una consulta SELECT de manera segura"""
        try:
            if not self.is_connected():
                self.reconnect()
                
            if not self.conn:
                raise Exception("No se pudo establecer conexión con la base de datos")
            
            cursor = self.conn.cursor(dictionary=True)
            cursor.execute(query)
            results = cursor.fetchall()
            cursor.close()
            
            return results
            
        except Exception as e:
            logger.error(f"Error ejecutando consulta SQL: {e}")
            raise
    
    def log_interaction(self, user_input: str, bot_response: str):
        """Registra la interacción en la base de datos"""
        try:
            if not self.is_connected():
                self.reconnect()
                
            if self.conn:
                cursor = self.conn.cursor()
                sql = """
                INSERT INTO chat_logs (user_input, bot_response, timestamp)
                VALUES (%s, %s, %s)
                """
                cursor.execute(sql, (user_input, bot_response, datetime.now()))
                self.conn.commit()
                cursor.close()
        except Exception as e:
            logger.error(f"Error registrando interacción: {e}")

class SQLQueryValidator:
    """Valida y sanitiza consultas SQL"""
    
    DANGEROUS_KEYWORDS = [
        'DROP', 'DELETE', 'UPDATE', 'INSERT', 'ALTER', 'TRUNCATE',
        'EXEC', 'EXECUTE', 'UNION', '--', ';', '/*', '*/', 'WAITFOR',
        'DELAY', 'SLEEP', 'BENCHMARK', 'LOAD_FILE', 'INTO OUTFILE',
        'INTO DUMPFILE', 'SHUTDOWN', 'KILL', 'GRANT', 'REVOKE'
    ]
    
    @staticmethod
    def is_safe_query(query: str) -> bool:
        """Verifica si una consulta SQL es segura"""
        query_upper = query.upper().strip()
        
        # Verificar que solo sea una consulta SELECT
        if not query_upper.startswith('SELECT'):
            logger.warning(f"Intento de ejecutar consulta no SELECT: {query}")
            return False
        
        # Verificar palabras clave peligrosas
        for keyword in SQLQueryValidator.DANGEROUS_KEYWORDS:
            if keyword in query_upper:
                logger.warning(f"Intento de inyección SQL detectado: {query}")
                return False
                
        return True
    
    @staticmethod
    def extract_sql_from_response(response: str) -> Optional[str]:
        """Extrae consulta SQL de la respuesta del modelo"""
        try:
            # Buscar bloques de código SQL
            sql_pattern = r'```sql\s*(.*?)\s*```'
            match = re.search(sql_pattern, response, re.DOTALL | re.IGNORECASE)
            
            if match:
                return match.group(1).strip()
            
            return None
        except Exception as e:
            logger.error(f"Error extrayendo SQL: {e}")
            return None

class ResponseFormatter:
    """Formatea las respuestas del chatbot"""
    
    @staticmethod
    def format_query_results(query: str, results: List[Dict]) -> str:
        """Formatea los resultados de una consulta"""
        if not results:
            return "📊 No se encontraron resultados para tu consulta."
        
        query_upper = query.upper()
        
        # Formateo específico para diferentes tipos de consultas
        if 'COUNT(*)' in query_upper and 'total' in results[0]:
            total = results[0]['total']
            if 'productos' in query.lower():
                return f"📊 Tienes {total} productos activos en el inventario."
            elif 'almacenes' in query.lower():
                return f"📊 Tienes {total} almacenes registrados."
            elif 'transferencias' in query.lower():
                return f"📊 Se han realizado {total} transferencias."
            else:
                return f"📊 Total encontrado: {total}"
        
        # Para consultas de stock bajo/alto
        if 'stock_minimo' in query.lower() or 'stock_maximo' in query.lower():
            if 'stock_minimo' in query.lower():
                title = "🔴 Productos con stock bajo:"
            else:
                title = "🟡 Productos con stock alto:"
            
            formatted_items = []
            for row in results:
                if 'nombre' in row and 'cantidad_disponible' in row:
                    formatted_items.append(f"• {row['nombre']}: {row['cantidad_disponible']} unidades")
            
            return f"{title}\n" + "\n".join(formatted_items)
        
        # Para productos sin stock
        if 'cantidad_disponible' in query.lower() and ('IS NULL' in query_upper or '= 0' in query):
            formatted_items = [f"• {row['nombre']}" for row in results if 'nombre' in row]
            return "🔴 Productos sin stock:\n" + "\n".join(formatted_items)
        
        # Para listados de productos
        if 'productos' in query.lower() and len(results) > 1:
            formatted_items = []
            for row in results:
                if 'nombre' in row:
                    item = f"• {row['nombre']}"
                    if 'categoria' in row:
                        item += f" ({row['categoria']})"
                    if 'precio_venta' in row:
                        item += f" - ${row['precio_venta']}"
                    formatted_items.append(item)
            
            return "📦 Productos encontrados:\n" + "\n".join(formatted_items[:10])
        
        # Para consultas de categorías/proveedores/almacenes
        if any(field in query.lower() for field in ['categoria', 'proveedor', 'almacen']):
            formatted_items = []
            for row in results:
                if len(row) == 2:  # Formato nombre: cantidad
                    key, value = list(row.items())
                    formatted_items.append(f"• {list(row.values())[0]}: {list(row.values())[1]}")
            
            if formatted_items:
                return "📊 Resultados:\n" + "\n".join(formatted_items)
        
        # Formato genérico para otras consultas
        if len(results) <= 5:
            formatted_response = "📊 Resultados encontrados:\n\n"
            for i, row in enumerate(results, 1):
                formatted_response += f"{i}. "
                formatted_response += " | ".join(f"{k}: {v}" for k, v in row.items()) + "\n"
            return formatted_response
        else:
            return f"📊 Se encontraron {len(results)} resultados. Mostrando los primeros 5:\n\n" + ResponseFormatter.format_query_results(query, results[:5])

class ConversationHandler:
    """Maneja el contexto y flujo de la conversación"""
    
    def __init__(self, max_history: int = 10):
        self.conversation_history: List[Message] = []
        self.max_history = max_history
    
    def add_message(self, role: str, content: str):
        """Agrega un mensaje al historial"""
        self.conversation_history.append(Message(role, content))
        
        # Mantener solo los últimos mensajes
        if len(self.conversation_history) > self.max_history:
            self.conversation_history = self.conversation_history[-self.max_history:]
    
    def get_context(self) -> str:
        """Obtiene el contexto de la conversación"""
        context = "Historial reciente de la conversación:\n"
        for msg in self.conversation_history[-5:]:
            context += f"{msg.role}: {msg.content}\n"
        return context
    
    def is_greeting(self, message: str) -> bool:
        """Verifica si el mensaje es un saludo"""
        greetings = ['hola', 'buenos días', 'buenas tardes', 'buenas noches', 'hey', 'hi']
        return message.lower().strip() in greetings
    
    def is_casual_chat(self, message: str) -> bool:
        """Verifica si el mensaje es charla casual"""
        casual_phrases = [
            'como estas', 'qué tal', 'como andas', 'bien o que', 'que pasa',
            'todo bien', 'como va', 'que tal todo', 'gracias', 'ok', 'bien'
        ]
        return any(phrase in message.lower() for phrase in casual_phrases)

class InventoryChatbot:
    def __init__(self):
        # Cargar configuración
        load_dotenv(Path(__file__).parent / 'config' / '.env')
        
        # Configurar base de datos
        db_config = {
            'host': os.getenv('DB_SERVER'),
            'user': os.getenv('DB_USERNAME'),
            'password': os.getenv('DB_PASSWORD'),
            'database': os.getenv('DB_DATABASE')
        }
        
        # Inicializar componentes
        self.db_manager = DatabaseManager(db_config)
        self.conversation_handler = ConversationHandler()
        self.api_key = os.getenv('GOOGLE_API_KEY')
        
        # Prompt del sistema optimizado
        self.system_prompt = self._get_system_prompt()
    
    def _get_system_prompt(self) -> str:
        """Retorna el prompt del sistema optimizado"""
        return """
        Eres un asistente especializado en consultas de inventario para el sistema IMS_invsys.

        REGLAS IMPORTANTES:
        1. SOLO ejecutas consultas SELECT para obtener información
        2. NO modifies, insertas o eliminas datos
        3. Respondes de manera natural y conversacional
        4. Para charla casual, responde amigablemente SIN ejecutar consultas SQL
        5. SOLO ejecutas SQL cuando el usuario pregunta específicamente por datos del inventario

        EJEMPLOS DE CUÁNDO NO EJECUTAR SQL:
        - "¿Cómo estás?" → Responder: "¡Estoy bien, gracias! ¿En qué puedo ayudarte con el inventario?"
        - "Todo bien" → Responder: "¡Perfecto! ¿Necesitas consultar algo del inventario?"
        - "Gracias" → Responder: "¡De nada! ¿Hay algo más en lo que pueda ayudarte?"

        EJEMPLOS DE CUÁNDO SÍ EJECUTAR SQL:
        - "¿Cuántos productos tengo?" → Ejecutar consulta de conteo
        - "Productos sin stock" → Ejecutar consulta de stock
        - "Listar almacenes" → Ejecutar consulta de almacenes

        Cuando necesites ejecutar una consulta, responde ÚNICAMENTE con:
        ```sql
        [CONSULTA SQL AQUÍ]
        ```

        CONSULTAS PRINCIPALES:
        - Contar productos: SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'
        - Productos sin stock: SELECT p.nombre FROM productos p LEFT JOIN stock_almacen sa ON p.id_producto = sa.id_producto WHERE (sa.cantidad_disponible IS NULL OR sa.cantidad_disponible = 0) AND p.estado = 'activo'
        - Listar almacenes: SELECT nombre, ubicacion FROM almacenes
        - Stock bajo: SELECT p.nombre, sa.cantidad_disponible, p.stock_minimo FROM stock_almacen sa JOIN productos p ON sa.id_producto = p.id_producto WHERE sa.cantidad_disponible <= p.stock_minimo AND p.estado = 'activo'
        """
    
    def _get_gemini_response(self, message: str, context: str) -> str:
        """Obtiene respuesta del modelo Gemini"""
        try:
            url = f'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={self.api_key}'
            
            data = {
                'contents': [{
                    'parts': [{
                        'text': f"{self.system_prompt}\n\n{context}\n\nUsuario: {message}"
                    }]
                }],
                'generationConfig': {
                    'temperature': 0.1,
                    'maxOutputTokens': 1024,
                    'topP': 0.9
                }
            }

            response = requests.post(url, json=data, timeout=10)
            response.raise_for_status()
            
            response_data = response.json()
            return response_data['candidates'][0]['content']['parts'][0]['text'].strip()
            
        except Exception as e:
            logger.error(f"Error en API de Gemini: {e}")
            return "Lo siento, no puedo procesar tu solicitud en este momento. Por favor, intenta nuevamente."
    
    def _should_execute_sql(self, message: str, ai_response: str) -> bool:
        """Determina si se debe ejecutar una consulta SQL"""
        # No ejecutar SQL para charla casual
        if self.conversation_handler.is_casual_chat(message):
            return False
        
        # No ejecutar si la respuesta no contiene SQL
        if '```sql' not in ai_response.lower():
            return False
        
        # Verificar si el mensaje pide información específica del inventario
        inventory_keywords = [
            'productos', 'stock', 'inventario', 'almacen', 'categoria', 'proveedor',
            'precio', 'cantidad', 'cuantos', 'listar', 'mostrar', 'consultar'
        ]
        
        return any(keyword in message.lower() for keyword in inventory_keywords)
    
    def handle_request(self, message: str) -> str:
        """Maneja una solicitud del usuario"""
        try:
            # Agregar mensaje del usuario al historial
            self.conversation_handler.add_message("user", message)
            
            # Manejar saludos
            if self.conversation_handler.is_greeting(message):
                response = "¡Hola! Soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?"
                self.conversation_handler.add_message("assistant", response)
                return response
            
            # Validar entrada
            if len(message.strip()) < 2:
                response = "¿Podrías ser más específico con tu pregunta?"
                self.conversation_handler.add_message("assistant", response)
                return response
            
            # Obtener contexto y respuesta de IA
            context = self.conversation_handler.get_context()
            ai_response = self._get_gemini_response(message, context)
            
            # Verificar si se debe ejecutar SQL
            if self._should_execute_sql(message, ai_response):
                sql_query = SQLQueryValidator.extract_sql_from_response(ai_response)
                
                if sql_query and SQLQueryValidator.is_safe_query(sql_query):
                    try:
                        results = self.db_manager.execute_query(sql_query)
                        response = ResponseFormatter.format_query_results(sql_query, results)
                    except Exception as e:
                        response = f"❌ Error al consultar la base de datos: {str(e)}"
                else:
                    response = "Lo siento, no puedo ejecutar esa consulta por razones de seguridad."
            else:
                # Usar la respuesta de IA directamente para charla casual
                response = ai_response
            
            # Agregar respuesta al historial
            self.conversation_handler.add_message("assistant", response)
            
            # Registrar interacción
            self.db_manager.log_interaction(message, response)
            
            return response
            
        except Exception as e:
            logger.error(f"Error procesando solicitud: {e}")
            return "Lo siento, hubo un error al procesar tu solicitud. Por favor, intenta nuevamente."
    
    def close(self):
        """Cierra las conexiones"""
        if self.db_manager.conn:
            self.db_manager.conn.close()

# Ejemplo de uso
def main():
    chatbot = InventoryChatbot()
    
    print("🤖 Chatbot de Inventario iniciado")
    print("Escribe 'salir' para terminar\n")
    
    try:
        while True:
            user_input = input("Tú: ").strip()
            
            if user_input.lower() in ['salir', 'exit', 'quit']:
                print("¡Hasta luego!")
                break
            
            if not user_input:
                continue
                
            response = chatbot.handle_request(user_input)
            print(f"Bot: {response}\n")
            
    except KeyboardInterrupt:
        print("\n¡Hasta luego!")
    finally:
        chatbot.close()

if __name__ == "__main__":
    main()