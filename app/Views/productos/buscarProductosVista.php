<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/buscarProducto.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle search panel
            const searchHeader = document.querySelector('.search-header');
            const searchCard = document.querySelector('.search-card');

            searchHeader.addEventListener('click', function() {
                searchCard.classList.toggle('expanded');
            });

            // Clear search form
            const clearBtn = document.getElementById('clearSearch');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    document.querySelectorAll('form input, form select').forEach(input => {
                        input.value = '';
                    });
                });
            }

            // Table search filter
            const tableSearch = document.getElementById('tableSearch');
            if (tableSearch) {
                tableSearch.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase();
                    const rows = document.querySelectorAll('table tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchValue) ? '' : 'none';
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
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="header-content">
                        <h1>Buscar Productos</h1>
                        <p>Encuentra y gestiona los productos del inventario</p>
                    </div>
                </div>
                <a href="../../Views/usuarios/index.php" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Search Card -->
        <div class="card search-card expanded">
            <div class="search-header">
                <div class="search-title">
                    <i class="fas fa-filter"></i> Filtros de búsqueda
                </div>
                <div class="toggle-icon">
                    <i class="fas fa-chevron-up"></i>
                </div>
            </div>
            <div class="search-body">
                <form action="../../Controller/productos/buscarProductosController.php" method="GET">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <div class="input-group">
                                <i class="fas fa-box input-icon"></i>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars(
                                    $_GET["nombre"] ?? ""
                                ); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="codigo" class="form-label">Código</label>
                            <div class="input-group">
                                <i class="fas fa-barcode input-icon"></i>
                                <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Buscar por código" value="<?php echo htmlspecialchars(
                                    $_GET["codigo"] ?? ""
                                ); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sku" class="form-label">SKU</label>
                            <div class="input-group">
                                <i class="fas fa-fingerprint input-icon"></i>
                                <input type="text" id="sku" name="sku" class="form-control" placeholder="Buscar por SKU" value="<?php echo htmlspecialchars(
                                    $_GET["sku"] ?? ""
                                ); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="categoria" class="form-label">Categoría</label>
                            <div class="input-group">
                                <i class="fas fa-folder input-icon"></i>
                                <select id="categoria" name="categoria" class="form-control">
                                    <option value="">Todas las categorías</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo htmlspecialchars(
                                            $cat["nombre"]
                                        ); ?>" <?php echo ($_GET["categoria"] ??
    "") ===
$cat["nombre"]
    ? "selected"
    : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $cat["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="unidad_medida" class="form-label">Unidad de Medida</label>
                            <div class="input-group">
                                <i class="fas fa-ruler input-icon"></i>
                                <select id="unidad_medida" name="unidad_medida" class="form-control">
                                    <option value="">Todas las unidades</option>
                                    <?php foreach ($unidades_medida as $um): ?>
                                        <option value="<?php echo htmlspecialchars(
                                            $um["nombre"]
                                        ); ?>" <?php echo ($_GET[
    "unidad_medida"
] ??
    "") ===
$um["nombre"]
    ? "selected"
    : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $um["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="clearSearch" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar Productos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($productos)): ?>
                    <!-- Table Controls -->
                    <div class="table-controls">
                        <div class="showing-entries">
                            Mostrando <strong><?php echo count(
                                $productos
                            ); ?></strong> productos
                        </div>
                        <div class="search-table">
                            <i class="fas fa-search search-table-icon"></i>
                            <input type="text" id="tableSearch" placeholder="Buscar en resultados...">
                        </div>
                    </div>

                    <!-- Table with results -->
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Stock Mínimo</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
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
                                        <td class="price-value"><?php echo htmlspecialchars(
                                            $producto["precio_compra"]
                                        ); ?></td>
                                        <td class="price-value"><?php echo htmlspecialchars(
                                            $producto["precio_venta"]
                                        ); ?></td>
                                        <td class="numeric-value"><?php echo htmlspecialchars(
                                            $producto["stock_minimo"]
                                        ); ?></td>
                                        <td>
                                            <span class="badge badge-primary">
                                                <?php echo htmlspecialchars(
                                                    $producto["categoria"]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="../../Controller/productos/verProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ] ?? ""; ?>"
                                                   class="action-btn view" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ] ?? ""; ?>"
                                                   class="action-btn edit" title="Editar producto">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/productos/eliminarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ] ?? ""; ?>"
                                                   class="action-btn delete" title="Eliminar producto"
                                                   onclick="return confirm('¿Está seguro que desea eliminar este producto?')">
                                                    <i class="fas fa-trash"></i>
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
                <?php else: ?>
                    <!-- Empty state -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron productos</h3>
                        <p>No hay productos que coincidan con los criterios de búsqueda. Intente con diferentes términos o añada un nuevo producto al inventario.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Nuevo Producto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
