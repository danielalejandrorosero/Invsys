
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar texto de ayuda según el tipo de ajuste
    var radioButtons = document.querySelectorAll('input[name="tipo_ajuste"]');
    var cantidadHelper = document.getElementById('cantidad-helper');
    var ajusteInfo = document.getElementById('ajuste-info');
    
    function updateHelperText() {
        var selectedType = document.querySelector('input[name="tipo_ajuste"]:checked').value;
        
        if (selectedType === 'absoluto') {
            cantidadHelper.textContent = 'Ingrese la nueva cantidad total de este producto en el almacén seleccionado';
            ajusteInfo.innerHTML = '<strong>Nota:</strong> Este ajuste sobrescribirá la cantidad actual del producto en el almacén seleccionado.';
        } else if (selectedType === 'incremento') {
            cantidadHelper.textContent = 'Ingrese la cantidad a añadir al stock actual';
            ajusteInfo.innerHTML = '<strong>Nota:</strong> Esta cantidad se sumará al stock actual del producto en el almacén.';
        } else if (selectedType === 'decremento') {
            cantidadHelper.textContent = 'Ingrese la cantidad a restar del stock actual';
            ajusteInfo.innerHTML = '<strong>Nota:</strong> Esta cantidad se restará del stock actual del producto en el almacén. Se verificará que hay suficiente stock.';
        }
    }
    
    radioButtons.forEach(function(radio) {
        radio.addEventListener('change', updateHelperText);
    });
    
    // Ejecutar al cargar la página
    updateHelperText();

    // Focus the first select when the page loads
    const firstSelect = document.querySelector('select');
    if (firstSelect) {
        firstSelect.focus();
    }

    // Validación del formulario
    document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
        if (this.querySelector('input[name="ajustar_stock"]')) {
            const cantidad = parseInt(document.getElementById('cantidad').value);
            const tipoAjuste = document.querySelector('input[name="tipo_ajuste"]:checked').value;
            
            if (tipoAjuste === 'incremento' || tipoAjuste === 'decremento') {
                if (cantidad <= 0) {
                    e.preventDefault();
                    alert('Para incrementar o disminuir stock, la cantidad debe ser mayor que 0.');
                    return false;
                }
            }
            
            if (cantidad < 0) {
                e.preventDefault();
                alert('La cantidad no puede ser negativa.');
                return false;
            }
        }
    });
});
