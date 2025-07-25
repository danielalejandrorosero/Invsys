document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const openChatBtn = document.getElementById('open-chat-btn');
    const closeChatBtn = document.getElementById('close-chat-btn');
    const chatContainer = document.getElementById('chat-container');
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input-field');
    const sendChatBtn = document.getElementById('send-chat-btn');

    // Mostrar/ocultar el chat
    openChatBtn.addEventListener('click', function() {
        chatContainer.style.display = 'flex';
        openChatBtn.style.display = 'none';
        chatInput.focus();
    });

    closeChatBtn.addEventListener('click', function() {
        chatContainer.style.display = 'none';
        openChatBtn.style.display = 'block';
        // Limpiar el historial de mensajes
        chatMessages.innerHTML = '<div>Hola, soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?</div>';
        // Limpiar el campo de entrada
        chatInput.value = '';
    });

    // Enviar mensaje al presionar Enter
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Enviar mensaje al hacer clic en el botón
    sendChatBtn.addEventListener('click', sendMessage);

    // Función para enviar mensaje
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message === '') return;

        // Agregar mensaje del usuario al chat
        addMessage(message, 'user');
        chatInput.value = '';

        // Mostrar indicador de escritura
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator';
        typingIndicator.innerHTML = '<span></span><span></span><span></span>';
        chatMessages.appendChild(typingIndicator);
        scrollToBottom();

        // Enviar mensaje al servidor
        fetch('/InventoryManagementSystem/chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            console.log('Respuesta del servidor:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            // Eliminar indicador de escritura
            chatMessages.removeChild(typingIndicator);
            
            // Agregar respuesta del bot
            addMessage(data.response, 'bot');
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            // Eliminar indicador de escritura
            chatMessages.removeChild(typingIndicator);
            
            // Mostrar error específico sobre el servicio de Gemini
            addMessage('Error: No se pudo conectar con el servicio de IA. Por favor, asegúrate de que el servicio Flask esté corriendo y que la API key de Gemini esté configurada correctamente.', 'bot');
        });
    }

    // Función para agregar mensaje al chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        // Formatear el texto si es del bot (puede contener markdown)
        if (sender === 'bot') {
            // Reemplazar ** con etiquetas strong
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            // Formatear bloques de código SQL
            text = text.replace(/```sql([\s\S]*?)```/g, '<pre class="sql-code">$1</pre>');
            
            // Formatear saltos de línea
            text = text.replace(/\n/g, '<br>');
        }
        
        messageDiv.innerHTML = text;
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Función para desplazarse al final del chat
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});