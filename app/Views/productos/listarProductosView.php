<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .btn-small i {
            font-size: 14px;
        }
        .price {
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.products-table tbody tr').forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
            const modals = document.querySelectorAll('.modal');
            M.Modal.init(modals);
        });

        function showDeleteModal(id, nombre, codigo) {
            if (confirm(`¿Estás seguro de eliminar el producto "${nombre}" (Código: ${codigo})?`)) {
                window.location.href = `../../Controller/productos/eliminarProductoController.php?id=${id}`;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-boxes"></i> Inventario de Productos
                </span>
                <p>Gestione todos los productos disponibles en el sistema</p>
                <div class="right-align" style="margin-top: 10px;">
                    <a href="../../Controller/productos/agregarProductoController.php" class="btn green">
                        <i class="fas fa-plus left"></i> Agregar Producto
                    </a>
                    <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="btn blue">
                        <i class="fas fa-trash-restore left"></i> Ver Eliminados
                    </a>
                    <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                        <i class="fas fa-home left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensajes de Sesión -->
        <div class="card">
            <div class="card-content">
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="card-panel green lighten-4 green-text text-darken-4">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $_SESSION["mensaje"]; ?></span>
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

                <!-- Filtros y Exportación -->
                <div class="row">
                    <div class="col s12 m6">
                        <button class="btn orange">
                            <i class="fas fa-file-export left"></i> Exportar
                        </button>
                        <button class="btn purple">
                            <i class="fas fa-print left"></i> Imprimir
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
                    <!-- Sin productos -->
                    <div class="center-align">
                        <i class="fas fa-box-open fa-3x"></i>
                        <h5>No hay productos registrados</h5>
                        <p>Comienza agregando un nuevo producto.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn green">
                            <i class="fas fa-plus left"></i> Agregar Producto
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                        <table class="highlight products-table">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Compra</th>
                                    <th>Venta</th>
                                    <th>Categoría</th>
                                    <th>Proveedor</th>
                                    <th>Stock Min/Max</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td>
                                            <img src="../../../public/uploads/imagenes/productos/<?php echo $producto["imagen_destacada"]; ?>" alt="Producto" style="width: 50px; height: 50px; border-radius: 8px;">
                                        </td>
                                        <td><?php echo htmlspecialchars($producto["nombre"]); ?></td>
                                        <td><?php echo htmlspecialchars($producto["codigo"]); ?></td>
                                        <td><?php echo htmlspecialchars($producto["sku"]); ?></td>
                                        <td class="price">$<?php echo number_format($producto["precio_compra"], 2); ?></td>
                                        <td class="price">$<?php echo number_format($producto["precio_venta"], 2); ?></td>
                                        <td><?php echo htmlspecialchars($producto["categoria_nombre"]); ?></td>
                                        <td><?php echo htmlspecialchars($producto["proveedor_nombre"]); ?></td>
                                        <td>
                                            Min: <?php echo $producto["stock_minimo"]; ?> /
                                            Max: <?php echo $producto["stock_maximo"]; ?>
                                        </td>
                                        <td>
                                            <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto["id_producto"]; ?>" class="btn-floating btn-small blue" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn-floating btn-small red" title="Eliminar"
                                                onclick="showDeleteModal('<?php echo $producto["id_producto"]; ?>', '<?php echo addslashes($producto["nombre"]); ?>', '<?php echo $producto["codigo"]; ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <ul class="pagination center-align">
                        <li class="<?php echo $page > 1 ? 'waves-effect' : 'disabled'; ?>">
                            <a href="?page=<?php echo max(1, $page - 1); ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="<?php echo $i === $page ? 'active' : 'waves-effect'; ?>">
                                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="<?php echo $page < $totalPaginas ? 'waves-effect' : 'disabled'; ?>">
                            <a href="?page=<?php echo min($totalPaginas, $page + 1); ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
