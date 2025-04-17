<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Eliminados | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Table search
            const searchInput = document.getElementById('tableSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('.products-table tbody tr');

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
                    <i class="fas fa-trash"></i> Productos Eliminados
                </span>
                <p>Productos que han sido removidos del inventario activo</p>
                <div class="right-align">
                    <a href="../../Controller/productos/ListarProductosController.php" class="btn waves-effect waves-light">
                        <i class="fas fa-box"></i> Ver Productos Activos
                    </a>
                    <a href="../../Views/usuarios/index.php" class="btn waves-effect waves-light grey">
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
                        <h5>Productos en la papelera</h5>
                        <p>Esta sección muestra los productos que han sido eliminados. Puede restaurarlos para que vuelvan a estar disponibles en el inventario activo.</p>
                    </div>
                </div>

                <?php if (empty($productos)): ?>
                    <!-- Empty State -->
                    <div class="center-align">
                        <i class="fas fa-trash-alt fa-3x"></i>
                        <h5>No hay productos eliminados</h5>
                        <p>Actualmente no hay productos en la papelera. Los productos que elimine aparecerán en esta lista.</p>
                        <a href="../../Controller/productos/ListarProductosController.php" class="btn waves-effect waves-light">
                            <i class="fas fa-box"></i> Ver Productos Activos
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
                                <input type="text" id="tableSearch" placeholder="Buscar producto...">
                            </div>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="table-responsive">
                        <table class="highlight products-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estado</th>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Categoría</th>
                                    <th>Proveedor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(
                                            $producto["id_producto"]
                                        ); ?></td>
                                        <td>
                                            <span class="new badge red" data-badge-caption="">
                                                <i class="fas fa-trash"></i> Eliminado
                                            </span>
                                        </td>
                                        <td>
                                            <div class="truncate" title="<?php echo htmlspecialchars(
                                                $producto["nombre"]
                                            ); ?>">
                                                <?php echo htmlspecialchars(
                                                    $producto["nombre"]
                                                ); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars(
                                            $producto["codigo"]
                                        ); ?></td>
                                        <td><?php echo htmlspecialchars(
                                            $producto["sku"]
                                        ); ?></td>
                                        <td class="price">$<?php echo number_format(
                                            $producto["precio_compra"],
                                            2
                                        ); ?></td>
                                        <td class="price">$<?php echo number_format(
                                            $producto["precio_venta"],
                                            2
                                        ); ?></td>
                                        <td>
                                            <span class="new badge blue" data-badge-caption="">
                                                <?php echo htmlspecialchars(
                                                    $producto[
                                                        "categoria_nombre"
                                                    ]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="truncate" title="<?php echo htmlspecialchars(
                                                $producto["proveedor_nombre"]
                                            ); ?>">
                                                <?php echo htmlspecialchars(
                                                    $producto[
                                                        "proveedor_nombre"
                                                    ]
                                                ); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="../../Controller/productos/RestaurarProductoController.php?id=<?php echo $producto[
                                                "id_producto"
                                            ]; ?>" class="btn-floating btn-small waves-effect waves-light green" title="Restaurar">
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
