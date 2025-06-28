document.addEventListener('DOMContentLoaded', function() {
    // Initialize Materialize select elements
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    // Price input formatting
    const priceInputs = document.querySelectorAll('.price-input');

    priceInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Remove non-numeric characters except decimal point
            let value = this.value.replace(/[^\d.]/g, '');

            // Ensure only one decimal point
            const decimalCount = (value.match(/\./g) || []).length;
            if (decimalCount > 1) {
                const parts = value.split('.');
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            // Format with two decimal places
            if (value.includes('.')) {
                const parts = value.split('.');
                if (parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                    value = parts.join('.');
                }
            }

            this.value = value;
        });
    });

    // Confirmation for cancellation
    const cancelBtn = document.getElementById('cancelBtn');

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea cancelar la edición? Los cambios no guardados se perderán.')) {
                e.preventDefault();
            }
        });
    }

    // Confirmation for deletion
    const deleteBtn = document.getElementById('deleteBtn');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea eliminar este producto? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    }
});