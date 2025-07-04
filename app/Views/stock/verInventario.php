<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <div class="container">
        <!-- Header Card -->
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-warehouse"></i> Inventario del Almacén
                </span>
                <p>Control y gestión de productos disponibles en el inventario</p>
                <div class="right-align">
                    <a href="../../Controller/stock/productosSinAlmacenController.php" class="btn waves-effect waves-light orange">
                        <i class="fas fa-exclamation-triangle"></i> Sin Almacén
                    </a>
                    <a href="../../Controller/stock/ajustarStockController.php" class="btn waves-effect waves-light green">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <a href="../../Controller/stock/movimientoStockController.php" class="btn waves-effect waves-light blue">
                        <i class="fas fa-exchange-alt"></i> Movimientos
                    </a>
                    <a href="../../Views/usuarios/dashboard.php" class="btn waves-effect waves-light grey">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-content">
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col s12 m6 l3">
                        <div class="card-panel">
                            <div class="center-align">
                                <i class="fas fa-boxes fa-2x primary-text"></i>
                                <h5><?php echo count($inventario) ?? 0; ?></h5>
                                <p>Total de productos</p>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="card-panel">
                            <div class="center-align">
                                <i class="fas fa-check-circle fa-2x green-text"></i>
                                <h5>
                                    <?php
                                    $okStock = 0;
                                    if (!empty($inventario)) {
                                        foreach ($inventario as $item) {
                                            if (
                                                isset($item["estado"]) &&
                                                $item["estado"] === "ok"
                                            ) {
                                                $okStock++;
                                            }
                                        }
                                    }
                                    echo $okStock;
                                    ?>
                                </h5>
                                <p>Productos con stock adecuado</p>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="card-panel">
                            <div class="center-align">
                                <i class="fas fa-exclamation-triangle fa-2x orange-text"></i>
                                <h5>
                                    <?php
                                    $lowStock = 0;
                                    if (!empty($inventario)) {
                                        foreach ($inventario as $item) {
                                            if (
                                                isset($item["cantidad"]) &&
                                                isset($item["stock_minimo"]) &&
                                                $item["cantidad"] <=
                                                    $item["stock_minimo"]
                                            ) {
                                                $lowStock++;
                                            }
                                        }
                                    }
                                    echo $lowStock;
                                    ?>
                                </h5>
                                <p>Productos con stock bajo</p>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="card-panel">
                            <div class="center-align">
                                <i class="fas fa-times-circle fa-2x red-text"></i>
                                <h5>
                                    <?php
                                    $outOfStock = 0;
                                    if (!empty($inventario)) {
                                        foreach ($inventario as $item) {
                                            if (
                                                isset($item["cantidad"]) &&
                                                $item["cantidad"] == 0
                                            ) {
                                                $outOfStock++;
                                            }
                                        }
                                    }
                                    echo $outOfStock;
                                    ?>
                                </h5>
                                <p>Productos sin stock</p>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 m6 l3">
                        <div class="card-panel">
                            <div class="center-align">
                                <i class="fas fa-exclamation-triangle fa-2x orange-text"></i>
                                <h5>
                                    <?php
                                    $sinAlmacen = 0;
                                    if (isset($stockModel)) {
                                        $sinAlmacen = $stockModel->contarProductosSinAlmacen();
                                    }
                                    echo $sinAlmacen;
                                    ?>
                                </h5>
                                <p>Sin asignar a almacén</p>
                            </div>
                        </div>
                    </div>
                </div>

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

                <?php if (empty($inventario)): ?>
                    <!-- Empty State -->
                    <div class="center-align">
                        <i class="fas fa-box-open fa-3x"></i>
                        <h5>No hay productos en este almacén</h5>
                        <p>Actualmente no hay productos registrados en este almacén. Puede comenzar agregando productos al inventario.</p>
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn waves-effect waves-light green">
                            <i class="fas fa-plus"></i> Ajustar Stock
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="highlight inventory-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Nivel de Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventario as $item):

                                    // Valores por defecto más conservadores y realistas
                                    $stock_minimo = $item["stock_minimo"] ?? 5;  // Mínimo más bajo
                                    $stock_maximo = $item["stock_maximo"] ?? 50; // Máximo más conservador
                                    $cantidad = $item["cantidad"] ?? 0;

                                    // Calcular el porcentaje de stock basado en el stock máximo
                                    $porcentaje_stock = 0;
                                    if ($stock_maximo > 0) {
                                        $porcentaje_stock = round(($cantidad / $stock_maximo) * 100);
                                        // Limitar el porcentaje a 100%
                                        $porcentaje_stock = min($porcentaje_stock, 100);
                                    }

                                    // Determinar el estado del stock
                                    $stockStatus = "green";
                                    $stockLabel = "Adecuado";

                                    if ($cantidad <= 0) {
                                        $stockStatus = "red";
                                        $stockLabel = "Sin Stock";
                                    } elseif ($cantidad <= $stock_minimo) {
                                        $stockStatus = "orange";
                                        $stockLabel = "Bajo";
                                    }

                                    // Destacar visualmente los productos sin stock
                                    $rowClass = $cantidad <= 0 ? 'class="red lighten-5"' : '';
                                    
                                    $firstLetter = mb_substr(
                                        $item["producto"],
                                        0,
                                        1,
                                        "UTF-8"
                                    );
                                    ?>
                                    <tr <?php echo $rowClass; ?>>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar circle">
                                                    <?php echo strtoupper(
                                                        $firstLetter
                                                    ); ?>
                                                </div>
                                                <div class="truncate" title="<?php echo htmlspecialchars(
                                                    $item["producto"]
                                                ); ?>">
                                                    <?php echo htmlspecialchars(
                                                        $item["producto"]
                                                    ); ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="stock-value"><?php echo htmlspecialchars(
                                                $cantidad
                                            ); ?></span>
                                            <small class="grey-text">
                                                (Min: <?php echo $stock_minimo; ?> | Max: <?php echo $stock_maximo; ?>)
                                            </small>
                                        </td>
                                        <td>
                                            <span class="new badge <?php echo $stockStatus; ?>" data-badge-caption=""><?php echo $stockLabel; ?></span>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="determinate" style="width: <?php echo $porcentaje_stock; ?>%"></div>
                                            </div>
                                            <span><?php echo $porcentaje_stock; ?>%</span>
                                        </td>
                                        <td>
                                            <a href="../../Controller/stock/ajustarStockController.php?id_producto=<?php echo $item[
                                                "id_producto"
                                            ] ??
                                                ""; ?>" class="btn-floating btn-small waves-effect waves-light blue" title="Ajustar Stock">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                        </td>
                                    </tr>
                                <?php
                                endforeach; ?>
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

                <!-- Productos Sin Almacén -->
                <?php 
                // Obtener productos sin almacén
                $productosSinAlmacen = [];
                if (isset($stockModel)) {
                    $productosSinAlmacen = $stockModel->obtenerProductosSinAlmacen();
                }
                ?>
                
                <?php if (!empty($productosBajoStock)): ?>
                    var productos = <?php echo json_encode($productosBajoStock); ?>;
                    productos.forEach(function (producto, index) {
                        setTimeout(function() {
                            M.toast({
                                html: '<i class="fas fa-exclamation-circle"></i> <b>Stock bajo:</b> ' + producto.nombre + ' - Cantidad: ' + producto.cantidad_disponible,
                                classes: 'orange darken-2',
                                displayLength: 8000
                            });
                        }, index * 300 + 1000);
                    });
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/verInventario.js"></script>
</body>
</html>
