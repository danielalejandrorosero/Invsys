document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.products-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    const modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});

function showDeleteModal(id, nombre, codigo) {
    if (confirm(`¿Estás seguro de eliminar el producto "${nombre}" (Código: ${codigo})?`)) {
        window.location.href = `../../Controller/productos/eliminarProductoController.php?id=${id}`;
    }
}
