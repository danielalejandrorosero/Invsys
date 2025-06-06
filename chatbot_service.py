from flask import Flask, request, jsonify
from flask_cors import CORS
from chatbot import InventoryChatbot
import logging

# Configurar logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)  # Permitir peticiones desde cualquier origen
chatbot = InventoryChatbot()

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.get_json()
        if not data or 'message' not in data:
            return jsonify({'error': 'Mensaje no proporcionado'}), 400

        logger.info(f"Mensaje recibido: {data['message']}")
        response = chatbot.handle_request(data['message'])
        logger.info(f"Respuesta generada: {response}")
        
        return jsonify({'response': response})
    except Exception as e:
        logger.error(f"Error en el chatbot: {str(e)}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    logger.info("Iniciando servidor Flask en http://127.0.0.1:5005")
    app.run(host='127.0.0.1', port=5005, debug=True) 