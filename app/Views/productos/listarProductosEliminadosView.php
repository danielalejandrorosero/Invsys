<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Eliminados | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/listarProductosEliminados.css">
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
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-trash"></i>
                    </div>
                    <div class="header-content">
                        <h1>Productos Eliminados</h1>
                        <p>Productos que han sido removidos del inventario activo</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="../../Controller/productos/ListarProductosController.php" class="btn btn-primary">
                        <i class="fas fa-box"></i> Ver Productos Activos
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div><?php echo $_SESSION["mensaje"]; ?></div>
                    </div>
                    <?php unset($_SESSION["mensaje"]); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="alert alert-danger">
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
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div class="info-box-content">
                        <h3>Productos en la papelera</h3>
                        <p>Esta sección muestra los productos que han sido eliminados. Puede restaurarlos para que vuelvan a estar disponibles en el inventario activo.</p>
                    </div>
                </div>

                <?php if (empty($productos)): ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-trash-alt"></i>
                        <h3>No hay productos eliminados</h3>
                        <p>Actualmente no hay productos en la papelera. Los productos que elimine aparecerán en esta lista.</p>
                        <a href="../../Controller/productos/ListarProductosController.php" class="btn btn-primary">
                            <i class="fas fa-box"></i> Ver Productos Activos
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Table Controls -->
                    <div class="table-controls">
                        <div class="table-actions">
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-trash-restore"></i> Restaurar Todos
                            </button>
                        </div>
                        <div class="search-table">
                            <i class="fas fa-search"></i>
                            <input type="text" id="tableSearch" placeholder="Buscar producto...">
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="table-responsive">
                        <table class="products-table">
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
                                            <span class="badge badge-danger">
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
                                            <span class="badge badge-primary">
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
                                            <div class="row-actions">
                                                <a href="../../Controller/productos/RestaurarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ]; ?>" class="btn btn-success btn-sm" title="Restaurar">
                                                    <i class="fas fa-trash-restore"></i> Restaurar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="page-item disabled">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                        <div class="page-item active">1</div>
                        <div class="page-item disabled">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
