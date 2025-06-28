<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/buscarProductos.css">
</head>
<body>
    <div class="container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-search"></i> Buscar Productos
                </span>
                <p>Encuentra y gestiona los productos del inventario</p>
                <a href="../../Views/usuarios/dashboard.php" class="btn-floating btn-small waves-effect waves-light red">
                    <i class="fas fa-home"></i>
                </a>
            </div>
        </div>

        <!-- Search Card -->
        <div class="card search-card expanded">
            <div class="card-content">
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
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-box prefix"></i>
                                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars(
                                    $_GET["nombre"] ?? ""
                                ); ?>">
                                <label for="nombre">Nombre del Producto</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-barcode prefix"></i>
                                <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars(
                                    $_GET["codigo"] ?? ""
                                ); ?>">
                                <label for="codigo">Código</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-fingerprint prefix"></i>
                                <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars(
                                    $_GET["sku"] ?? ""
                                ); ?>">
                                <label for="sku">SKU</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-folder prefix"></i>
                                <select id="categoria" name="categoria">
                                    <option value="" disabled selected>Todas las categorías</option>
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
                                <label for="categoria">Categoría</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-ruler prefix"></i>
                                <select id="unidad_medida" name="unidad_medida">
                                    <option value="" disabled selected>Todas las unidades</option>
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
                                <label for="unidad_medida">Unidad de Medida</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" id="clearSearch" class="btn waves-effect waves-light red">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="fas fa-search"></i> Buscar Productos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Card -->
        <div class="card">
            <div class="card-content">
                <?php if (!empty($productos)): ?>
                    <!-- Table Controls -->
                    <div class="row">
                        <div class="col s12 m6">
                            Mostrando <strong><?php echo count(
                                $productos
                            ); ?></strong> productos
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <i class="fas fa-search prefix"></i>
                                <input type="text" id="tableSearch" placeholder="Buscar en resultados...">
                            </div>
                        </div>
                    </div>

                    <!-- Table with results -->
                    <div class="table-responsive">
                        <table class="highlight">
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
                                            <span class="new badge blue" data-badge-caption="">
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
                                                   class="btn-floating btn-small waves-effect waves-light blue" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ] ?? ""; ?>"
                                                   class="btn-floating btn-small waves-effect waves-light orange" title="Editar producto">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/productos/eliminarProductoController.php?id=<?php echo $producto[
                                                    "id_producto"
                                                ] ?? ""; ?>"
                                                   class="btn-floating btn-small waves-effect waves-light red" title="Eliminar producto"
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
                    <ul class="pagination">
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="active"><a href="#!">1</a></li>
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                <?php else: ?>
                    <!-- Empty state -->
                    <div class="center-align">
                        <i class="fas fa-search fa-3x"></i>
                        <h5>No se encontraron productos</h5>
                        <p>No hay productos que coincidan con los criterios de búsqueda. Intente con diferentes términos o añada un nuevo producto al inventario.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn waves-effect waves-light">
                            <i class="fas fa-plus"></i> Agregar Nuevo Producto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/buscarProductos.js"></script>
</body>
</html>