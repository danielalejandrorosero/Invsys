// Script para mantener la sesión activa y actualizar last_login
(function() {
    'use strict';
    
    // Solo ejecutar si el usuario está autenticado
    if (typeof session_id === 'undefined') {
        return;
    }
    
    // Función para hacer ping al servidor
    function keepSessionAlive() {
        fetch('../app/Controller/usuarios/keepAliveController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'keepalive'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                // Si la sesión ya no es válida, redirigir al login
                window.location.href = '../public/index.php';
            }
        })
        .catch(error => {
            console.log('Error al mantener sesión activa:', error);
        });
    }
    
    // Ejecutar cada 5 minutos (300,000 ms)
    setInterval(keepSessionAlive, 300000);
    
    // También ejecutar cuando el usuario regresa a la pestaña
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            keepSessionAlive();
        }
    });
    
    // Ejecutar una vez al cargar la página
    keepSessionAlive();
})(); 