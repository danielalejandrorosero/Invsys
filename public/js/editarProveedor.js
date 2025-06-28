
// Validación del lado del cliente
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    const requiredFields = ['nombre', 'contacto', 'direccion', 'telefono', 'email'];
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        const value = field.value.trim();
        
        if (!value) {
            field.classList.add('input-error');
            valid = false;
        } else {
            field.classList.remove('input-error');
        }
    });
    
    // Validación específica del email
    const emailField = document.getElementById('email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (emailField.value && !emailPattern.test(emailField.value)) {
        emailField.classList.add('input-error');
        valid = false;
    }
    
    if (!valid) {
        e.preventDefault();
        alert('Por favor, complete todos los campos requeridos correctamente.');
    }
});

// Remover clase de error cuando el usuario empiece a escribir
document.querySelectorAll('input, textarea').forEach(function(field) {
    field.addEventListener('input', function() {
        this.classList.remove('input-error');
    });
});

// Efectos visuales adicionales
document.querySelectorAll('input, textarea').forEach(function(field) {
    field.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });

    field.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});
