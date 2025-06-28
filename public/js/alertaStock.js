function closeModal() {
    document.getElementById('alertModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('alertModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Auto-cerrar después de 30 segundos si no hay interacción
setTimeout(function() {
    if (document.getElementById('alertModal').style.display !== 'none') {
        closeModal();
    }
}, 30000);
