<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/listarProductos.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search function
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

            // Delete confirmation modal
            const modal = document.getElementById('deleteModal');
            const productNameEl = document.getElementById('productName');
            const productCodeEl = document.getElementById('productCode');
            const productIdEl = document.getElementById('productId');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const cancelDeleteBtn = document.getElementById('cancelDelete');

            // Function to show modal
            window.showDeleteModal = function(id, name, code) {
                productNameEl.textContent = name;
                productCodeEl.textContent = code;
                productIdEl.textContent = id;
                modal.classList.add('show');

                // Update confirm button action
                confirmDeleteBtn.onclick = function() {
                    window.location.href = '../../Controller/productos/eliminarProductoController.php?id=' + id;
                };
            };

            // Close modal
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function() {
                    modal.classList.remove('show');
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.remove('show');
                }
            });
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
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="header-content">
                        <h1>Inventario de Productos</h1>
                        <p>Gestione todos los productos disponibles en el sistema</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="../../Controller/productos/agregarProductoController.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </a>
                    <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="btn btn-info">
                        <i class="fas fa-trash-restore"></i> Ver Productos Eliminados
                    </a>
                    <a href="../../Views/usuarios/index.php" class="btn btn-secondary">
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

                <!-- Table Controls -->
                <div class="table-controls">
                    <div class="table-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                        <button class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                    <div class="search-table">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tableSearch" placeholder="Buscar producto...">
                    </div>
                </div>

                <?php if (empty($productos)): ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No hay productos registrados</h3>
                        <p>Aún no se han agregado productos al inventario. Puedes comenzar agregando un nuevo producto.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Agregar Producto
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Product Table -->
                    <div class="table-responsive">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Categoría</th>
                                    <th>Proveedor</th>
                                    <th>Stock Min/Max</th>
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
                                            <span class="badge badge-info">
                                                Min: <?php echo htmlspecialchars(
                                                    $producto["stock_minimo"]
                                                ); ?> /
                                                Max: <?php echo htmlspecialchars(
                                                    $producto["stock_maximo"]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="row-actions">
                                                <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ]; ?>" class="btn btn-info btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/productos/VerProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ]; ?>" class="btn btn-primary btn-sm" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm" title="Eliminar"
                                                        onclick="showDeleteModal('<?php echo $producto[
                                                            "id_producto"
                                                        ]; ?>',
                                                                              '<?php echo htmlspecialchars(
                                                                                  $producto[
                                                                                      "nombre"
                                                                                  ]
                                                                              ); ?>',
                                                                              '<?php echo htmlspecialchars(
                                                                                  $producto[
                                                                                      "codigo"
                                                                                  ]
                                                                              ); ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Confirmar Eliminación</h2>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el siguiente producto? Esta acción no se puede deshacer fácilmente.</p>

                <div class="product-info">
                    <div class="product-name" id="productName">Nombre del Producto</div>
                    <div class="product-details">
                        <div><strong>Código:</strong> <span id="productCode">XXXX</span></div>
                        <div><strong>ID:</strong> <span id="productId">1</span></div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button id="confirmDelete" class="btn-confirm-delete">
                        <i class="fas fa-trash"></i> Sí, Eliminar
                    </button>
                    <button id="cancelDelete" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
