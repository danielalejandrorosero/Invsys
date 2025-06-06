<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores Eliminados | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
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
    </script>
</head>
<body>
    <div class="container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-trash"></i> Proveedores Eliminados
                </span>
                <p>Proveedores que han sido removidos del sistema activo</p>
                <div class="right-align">
                    <a href="../../Controller/proveedores/ListarProveedoresController.php" class="btn waves-effect waves-light">
                        <i class="fas fa-truck"></i> Ver Proveedores Activos
                    </a>
                    <a href="../../Views/usuarios/dashboard.php" class="btn waves-effect waves-light grey">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="card-panel green lighten-4 green-text text-darken-4">
                        <i class="fas fa-check-circle"></i>
                        <div><?php echo $_SESSION["mensaje"]; ?></div>
                    </div>
                    <?php unset($_SESSION["mensaje"]); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="card-panel red lighten-4 red-text text-darken-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($_SESSION["errores"] as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION["errores"]); ?>
                <?php endif; ?>

                <!-- Info Box -->
                <div class="card-panel blue lighten-4 blue-text text-darken-4">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h5>Proveedores en la papelera</h5>
                        <p>Esta sección muestra los proveedores que han sido eliminados. Puede restaurarlos para que vuelvan a estar disponibles en el sistema activo.</p>
                    </div>
                </div>

                <?php if (empty($proveedores)): ?>
                    <!-- Empty State -->
                    <div class="center-align">
                        <i class="fas fa-trash-alt fa-3x"></i>
                        <h5>No hay proveedores eliminados</h5>
                        <p>Actualmente no hay proveedores en la papelera. Los proveedores que elimine aparecerán en esta lista.</p>
                        <a href="../../Controller/proveedores/ListarProveedoresController.php" class="btn waves-effect waves-light">
                            <i class="fas fa-truck"></i> Ver Proveedores Activos
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Table Controls -->
                    <div class="row">
                        <div class="col s12 m6">
                            <button class="btn waves-effect waves-light green" disabled>
                                <i class="fas fa-trash-restore"></i> Restaurar Todos
                            </button>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <i class="fas fa-search prefix"></i>
                                <input type="text" id="tableSearch" placeholder="Buscar proveedor...">
                            </div>
                        </div>
                    </div>

                    <!-- Proveedor Table -->
                    <div class="table-responsive">
                        <table class="highlight proveedores-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Información de Contacto</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <tr>
                                    <td>
                                        <div class="provider-name">
                                            <i class="fas fa-building"></i>
                                            <?php echo htmlspecialchars($proveedor['nombre'] ?? 'N/A'); ?>
                                        </div>
                                        </td>
                                        <td>
                                        <div class="contact-person">
                                            <i class="fas fa-user"></i>
                                            <?php echo htmlspecialchars($proveedor['contacto'] ?? 'N/A'); ?>
                                            </div>
                                        </td>
                                    <td>
                                        <div class="contact-info">
                                            <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($proveedor['email'] ?? 'N/A'); ?></div>
                                            <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($proveedor['telefono'] ?? 'N/A'); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                        <div class="address-info">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($proveedor['direccion'] ?? 'N/A'); ?>
                                            </div>
                                        </td>
                                    <td>
                                        <span class="badge red">Eliminado</span>
                                    </td>
                                        <td>
                                        <a href="../../Controller/proveedores/restaurarProveedor.php?id=<?php echo $proveedor['id_proveedor']; ?>" 
                                           class="btn-floating btn-small waves-effect waves-light green" 
                                           title="Restaurar">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <ul class="pagination">
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="active"><a href="#!">1</a></li>
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>