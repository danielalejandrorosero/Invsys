
// Función para confirmar eliminación
function confirmarEliminacion(id, nombre) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    
    modal.innerHTML = `
        <div class="modal-content">
            <i class="fas fa-exclamation-triangle modal-icon"></i>
            <h3 class="modal-title">¿Eliminar Proveedor?</h3>
            <p class="modal-text">¿Está seguro de que desea eliminar el proveedor "<strong>${nombre}</strong>"?</p>
            <p class="modal-text" style="color: var(--danger); font-size: 0.875rem;">Esta acción no se puede deshacer.</p>
            <div class="modal-actions">
                <button onclick="this.closest('.modal').remove()" class="btn btn-secondary">Cancelar</button>
                <button onclick="window.location.href='../../Controller/proveedores/eliminarProveedor.php?id=${id}'" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Función para filtrar la tabla (búsqueda en tiempo real)
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('proveedoresTable');
    
    if (!table) return;
    
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        // Buscar en nombre, contacto, email y teléfono
        for (let j = 1; j < cells.length - 1; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(filter)) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

// Función para limpiar búsqueda
function clearSearch() {
    document.getElementById('searchInput').value = '';
    filterTable();
}

// Auto-ocultar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
});