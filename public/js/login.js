// Función para alternar la visibilidad de la contraseña
function togglePassword() {
    const passwordField = document.getElementById('password');
    const icon = document.querySelector('.password-toggle i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Añadir efectos de animación cuando la página se carga
document.addEventListener('DOMContentLoaded', function() {
    // Efecto de presión en los botones
    const btn = document.querySelector('.login-button');
    btn.addEventListener('mousedown', function() {
        this.style.boxShadow = 'inset 2px 2px 5px var(--shadow-color), inset -2px -2px 5px var(--highlight-color)';
        this.style.transform = 'scale(0.98)';
    });
    
    btn.addEventListener('mouseup', function() {
        this.style.boxShadow = '5px 5px 10px var(--shadow-color), -5px -5px 10px var(--highlight-color)';
        this.style.transform = 'scale(1)';
    });
    
    btn.addEventListener('mouseleave', function() {
        this.style.boxShadow = '5px 5px 10px var(--shadow-color), -5px -5px 10px var(--highlight-color)';
        this.style.transform = 'scale(1)';
    });
    
    // Efecto de enfoque en los campos de entrada
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
    
    // Efecto de hover en el logo
    const logo = document.querySelector('.logo');
    logo.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
        this.style.transition = 'transform 0.3s ease';
    });
    
    logo.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});