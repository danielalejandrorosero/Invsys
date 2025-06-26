<?php
// Mostrar mensajes de sesión si existen
if (isset($_SESSION['mensaje'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['errores'])) {
    foreach ($_SESSION['errores'] as $error) {
        echo "<div class='alert alert-error'>" . htmlspecialchars($error) . "</div>";
    }
    unset($_SESSION['errores']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --secondary: #6b7280;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1f2937;
            --light: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
            --border-radius: 8px;
            --spacing-xs: 4px;
            --spacing-sm: 8px;
            --spacing-md: 16px;
            --spacing-lg: 24px;
            --spacing-xl: 32px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--dark);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-lg);
        }

        .page-header {
            background: var(--light);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .page-title i {
            color: var(--primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md) var(--spacing-lg);
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
        }

        .btn-secondary {
            background-color: var(--gray-100);
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
        }

        .btn-secondary:hover {
            background-color: var(--gray-200);
        }

        .btn-success {
            background-color: var(--success);
            color: var(--light);
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--light);
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-sm {
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: 0.75rem;
        }

        .search-container {
            background: var(--light);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .search-box {
            display: flex;
            gap: var(--spacing-md);
            align-items: center;
        }

        .search-box input {
            flex: 1;
            padding: var(--spacing-md);
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            background: var(--light);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .stats {
            background: var(--light);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
            text-align: center;
            color: var(--gray-600);
        }

        .table-container {
            background: var(--light);
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            overflow: hidden;
            margin-bottom: var(--spacing-lg);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background-color: var(--gray-50);
            color: var(--gray-800);
            padding: var(--spacing-lg) var(--spacing-md);
            font-weight: 600;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--gray-200);
        }

        .table td {
            padding: var(--spacing-lg) var(--spacing-md);
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--gray-50);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
        }

        .contact-info div {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .contact-info i {
            width: 16px;
            text-align: center;
        }

        .actions {
            display: flex;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }

        .no-data {
            text-align: center;
            padding: var(--spacing-xl) var(--spacing-lg);
            color: var(--gray-600);
        }

        .no-data i {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: var(--spacing-lg);
        }

        .no-data p {
            margin-bottom: var(--spacing-lg);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: var(--spacing-sm);
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            min-width: 44px;
            text-align: center;
            font-size: 0.875rem;
        }

        .pagination a {
            background: var(--light);
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
        }

        .pagination a:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .pagination .current {
            background: var(--primary);
            color: var(--light);
            border: 1px solid var(--primary);
        }

        .pagination .disabled {
            background: var(--gray-100);
            color: var(--gray-300);
            border: 1px solid var(--gray-200);
            cursor: not-allowed;
        }

        .alert {
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-md);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .provider-name {
            font-weight: 600;
            color: var(--primary);
        }

        .provider-id {
            font-weight: 600;
            color: var(--gray-600);
            font-family: 'Courier New', monospace;
        }

        @media (max-width: 768px) {
            .container {
                padding: var(--spacing-md);
            }

            .page-header {
                flex-direction: column;
                gap: var(--spacing-lg);
                text-align: center;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .search-box {
                flex-direction: column;
            }

            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 800px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: var(--light);
            padding: var(--spacing-xl);
            border-radius: var(--border-radius);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .modal-icon {
            font-size: 3rem;
            color: var(--warning);
            margin-bottom: var(--spacing-lg);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-md);
            color: var(--dark);
        }

        .modal-text {
            color: var(--gray-600);
            margin-bottom: var(--spacing-lg);
        }

        .modal-actions {
            display: flex;
            gap: var(--spacing-md);
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-truck"></i>
                Gestión de Proveedores
            </h1>
            <a href="../../Controller/proveedores/agregarProveedor.php" class="btn btn-success">
                <i class="fas fa-plus"></i>
                Nuevo Proveedor
            </a>
        </div>

        <!-- Buscador -->
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, email o teléfono..." onkeyup="filterTable()">
                <button type="button" class="btn btn-secondary" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats">
            <p>
                <i class="fas fa-chart-bar"></i>
                Total de proveedores: <strong><?php echo $totalProveedores; ?></strong> | 
                Página <strong><?php echo $page; ?></strong> de <strong><?php echo $totalPaginas; ?></strong>
            </p>
        </div>

        <!-- Tabla de proveedores -->
        <div class="table-container">
            <?php if (empty($proveedores)): ?>
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <p>No hay proveedores registrados en el sistema.</p>
                    <a href="../../Controller/proveedores/agregarProveedor.php" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Crear primer proveedor
                    </a>
                </div>
            <?php else: ?>
                <table class="table" id="proveedoresTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Información de Contacto</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td>
                                <div class="provider-name">
                                    <i class="fas fa-building"></i>
                                    <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="contact-person">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($proveedor['contacto']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($proveedor['email']); ?></div>
                                    <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($proveedor['telefono']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="address-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($proveedor['direccion']); ?>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="../../Controller/proveedores/editarProveedorController.php?id=<?php echo $proveedor["id_proveedor"]; ?>" class="btn btn-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmarEliminacion(<?php echo $proveedor['id_proveedor']; ?>, '<?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>')" 
                                        class="btn btn-danger btn-sm" title="Eliminar proveedor">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
        <div class="pagination">
            <!-- Botón Anterior -->
            <?php if ($page > 1): ?>
                <a href="?page=1" title="Primera página"><i class="fas fa-angle-double-left"></i></a>
                <a href="?page=<?php echo $page - 1; ?>" title="Página anterior"><i class="fas fa-angle-left"></i></a>
            <?php else: ?>
                <span class="disabled"><i class="fas fa-angle-double-left"></i></span>
                <span class="disabled"><i class="fas fa-angle-left"></i></span>
            <?php endif; ?>

            <!-- Números de página -->
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPaginas, $page + 2);
            
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <?php if ($page < $totalPaginas): ?>
                <a href="?page=<?php echo $page + 1; ?>" title="Página siguiente"><i class="fas fa-angle-right"></i></a>
                <a href="?page=<?php echo $totalPaginas; ?>" title="Última página"><i class="fas fa-angle-double-right"></i></a>
            <?php else: ?>
                <span class="disabled"><i class="fas fa-angle-right"></i></span>
                <span class="disabled"><i class="fas fa-angle-double-right"></i></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
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
    </script>
</body>
</html>