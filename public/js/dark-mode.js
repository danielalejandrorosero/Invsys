// Función para alternar el modo oscuro
function toggleDarkMode() {
    const body = document.body;
    
    // Alternar la clase dark-mode en el body
    body.classList.toggle('dark-mode');
    
    // Guardar la preferencia en localStorage
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
    
    // Cambiar el icono del botón
    const darkModeIcon = document.getElementById('dark-mode-icon');
    if (darkModeIcon) {
        darkModeIcon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // Mostrar notificación
    if (typeof M !== 'undefined' && M.toast) {
        M.toast({
            html: isDarkMode ? 
                '<i class="fas fa-moon"></i> Modo oscuro activado' : 
                '<i class="fas fa-sun"></i> Modo claro activado',
            displayLength: 2000
        });
    }
}

// Función para aplicar el modo oscuro según la preferencia guardada
function applyDarkMode() {
    // Verificar si el usuario ya ha establecido una preferencia
    const darkMode = localStorage.getItem('darkMode');
    
    // Si el modo oscuro estaba habilitado, aplicarlo
    if (darkMode === 'enabled') {
        document.body.classList.add('dark-mode');
        
        // Actualizar el icono si existe
        const darkModeIcon = document.getElementById('dark-mode-icon');
        if (darkModeIcon) {
            darkModeIcon.className = 'fas fa-sun';
        }
    }
}

// Función para inicializar el botón de modo oscuro
function initDarkModeButton() {
    // Buscar el botón existente en el dashboard
    const darkModeButton = document.querySelector('.dark-mode-toggle');
    
    if (darkModeButton) {
        // Agregar el evento click al botón existente
        darkModeButton.addEventListener('click', toggleDarkMode);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar el modo oscuro según la preferencia guardada
    applyDarkMode();
    
    // Inicializar el botón de modo oscuro
    initDarkModeButton();
    
    // Agregar clase para transiciones suaves
    document.body.classList.add('dark-mode-transition');
});