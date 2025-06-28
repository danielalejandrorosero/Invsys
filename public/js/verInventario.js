
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var elems = document.querySelectorAll('.tooltipped');
    var instances = M.Tooltip.init(elems);
    
    // Table search
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.inventory-table tbody tr');

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Initialize stock level bars
    const stockBars = document.querySelectorAll('.stock-bar');
    stockBars.forEach(bar => {
        const value = parseInt(bar.getAttribute('data-value'));
        const max = parseInt(bar.getAttribute('data-max')) || 100;
        const percentage = (value / max) * 100;
        bar.style.width = `${Math.min(percentage, 100)}%`;

        if (percentage < 30) {
            bar.classList.add('stock-low');
        }
    });
});
