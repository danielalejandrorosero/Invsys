<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                const instance = M.Modal.getInstance(modal);
                instance.open();

                // Update confirm button action
                confirmDeleteBtn.onclick = function() {
                    window.location.href = '../../Controller/productos/eliminarProductoController.php?id=' + id;
                };
            };

            // Initialize modal
            var elems = document.querySelectorAll('.modal');
            M.Modal.init(elems);
        });
    </script>
</head>
<body>
    <div class="container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-boxes"></i> Inventario de Productos
                </span>
                <p>Gestione todos los productos disponibles en el sistema</p>
                <div class="right-align">
                    <a href="../../Controller/productos/agregarProductoController.php" class="btn waves-effect waves-light green">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </a>
                    <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="btn waves-effect waves-light blue">
                        <i class="fas fa-trash-restore"></i> Ver Productos Eliminados
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

                <!-- Table Controls -->
                <div class="row">
                    <div class="col s12 m6">
                        <button class="btn waves-effect waves-light">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                        <button class="btn waves-effect waves-light">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                    <div class="col s12 m6">
                        <div class="input-field">
                            <i class="fas fa-search prefix"></i>
                            <input type="text" id="tableSearch" placeholder="Buscar producto...">
                        </div>
                    </div>
                </div>

                <?php if (empty($productos)): ?>
                    <!-- Empty State -->
                    <div class="center-align">
                        <i class="fas fa-box-open fa-3x"></i>
                        <h5>No hay productos registrados</h5>
                        <p>Aún no se han agregado productos al inventario. Puedes comenzar agregando un nuevo producto.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn waves-effect waves-light green">
                            <i class="fas fa-plus"></i> Agregar Producto
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Product Table -->
                    <div class="table-responsive">
                        <table class="highlight products-table">
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
                                            <span class="new badge green" data-badge-caption="">
                                                Min: <?php echo htmlspecialchars(
                                                    $producto["stock_minimo"]
                                                ); ?> /
                                                Max: <?php echo htmlspecialchars(
                                                    $producto["stock_maximo"]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                                                "id_producto"
                                            ]; ?>" class="btn-floating btn-small waves-effect waves-light blue" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../../Controller/productos/VerProductoController.php?id=<?php echo $producto[
                                                "id_producto"
                                            ]; ?>" class="btn-floating btn-small waves-effect waves-light green" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn-floating btn-small waves-effect waves-light red" title="Eliminar"
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h4><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación</h4>
            <p>¿Está seguro de que desea eliminar el siguiente producto? Esta acción no se puede deshacer fácilmente.</p>
            <div class="product-info">
                <div class="product-name" id="productName">Nombre del Producto</div>
                <div class="product-details">
                    <div><strong>Código:</strong> <span id="productCode">XXXX</span></div>
                    <div><strong>ID:</strong> <span id="productId">1</span></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="confirmDelete" class="modal-close waves-effect waves-green btn red">
                <i class="fas fa-trash"></i> Sí, Eliminar
            </button>
            <button id="cancelDelete" class="modal-close waves-effect waves-green btn grey">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
