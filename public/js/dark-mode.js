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

// Función para crear el botón de modo oscuro si no existe
function createDarkModeButton() {
    // Verificar si el botón ya existe
    if (!document.querySelector('.dark-mode-toggle')) {
        // Crear el botón
        const darkModeButton = document.createElement('div');
        darkModeButton.className = 'dark-mode-toggle waves-effect waves-light';
        darkModeButton.setAttribute('title', 'Cambiar modo claro/oscuro');
        
        // Determinar el icono inicial basado en el modo actual
        const isDarkMode = document.body.classList.contains('dark-mode');
        const iconClass = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
        
        // Agregar el icono al botón
        darkModeButton.innerHTML = `<i id="dark-mode-icon" class="${iconClass}"></i>`;
        
        // Agregar el evento click
        darkModeButton.addEventListener('click', toggleDarkMode);
        
        // Agregar el botón al body
        document.body.appendChild(darkModeButton);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar el modo oscuro según la preferencia guardada
    applyDarkMode();
    
    // Crear el botón de modo oscuro
    createDarkModeButton();
    
    // Agregar clase para transiciones suaves
    document.body.classList.add('dark-mode-transition');
});