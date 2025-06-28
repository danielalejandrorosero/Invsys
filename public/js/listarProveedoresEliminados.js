
document.addEventListener('DOMContentLoaded', function() {
    // Table search
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.proveedores-table tbody tr');

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
