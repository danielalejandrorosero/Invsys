
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Materialize
    M.updateTextFields();

    // Manejadores de exportación
    document.getElementById('exportPDF').addEventListener('click', function() {
        window.location.href = '../../Controller/exportarArchivos/exportarPDFController.php?filtro=' + 
            encodeURIComponent(JSON.stringify(getFilterParams()));
    });
    
    document.getElementById('exportExcel').addEventListener('click', function() {
        window.location.href = '../../Controller/exportarArchivos/exportarEXCELController.php?filtro=' + 
            encodeURIComponent(JSON.stringify(getFilterParams()));
    });
    
    document.getElementById('printReport').addEventListener('click', function() {
        window.print();
    });

    // Limpiar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.querySelectorAll('#filterForm input').forEach(input => {
            input.value = '';
        });
        document.getElementById('filterForm').submit();
    });

    // Función para obtener parámetros de filtro
    function getFilterParams() {
        return {
            almacen: document.getElementById('almacen').value,
            categoria: document.getElementById('categoria').value,
            proveedor: document.getElementById('proveedor').value
        };
    }

    // Ordenamiento de tabla (básico)
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            // Implementar lógica de ordenamiento si es necesario
            console.log('Ordenar por:', this.textContent.trim());
        });
    });
});
