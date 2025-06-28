
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const errorContainer = document.querySelector('.error-container');

    // Check if there are error messages and show the container
    const errorMessages = document.querySelectorAll('.error-message');
    if (errorMessages.length > 0) {
        errorContainer.classList.add('active');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
