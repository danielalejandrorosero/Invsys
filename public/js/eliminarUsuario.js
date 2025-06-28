
document.addEventListener('DOMContentLoaded', function() {
    // Control de habilitación del botón según checkbox
    const confirmCheckbox = document.getElementById('confirmDelete');
    const deleteButton = document.getElementById('btnEliminar');
    
    confirmCheckbox.addEventListener('change', function() {
        deleteButton.disabled = !this.checked;
    });
    
    // Confirmación adicional al eliminar
    deleteButton.addEventListener('click', function(e) {
        if (!confirm('¿Está seguro que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
            e.preventDefault();
        }
    });
    
    // Efecto ripple para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const x = e.clientX - e.target.getBoundingClientRect().left;
            const y = e.clientY - e.target.getBoundingClientRect().top;
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
