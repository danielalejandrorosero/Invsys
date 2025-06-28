<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inicializar variables por defecto
$productos = $productos ?? [];
$totalProductos = $totalProductos ?? 0;
$resultado = $resultado ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/reporteStock.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-chart-bar"></i> Reporte de Stock</h4>
                        <p>Análisis detallado del inventario actual por producto, almacén y categoría</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn green" id="exportExcel">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button class="btn red" id="exportPDF">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <a href="../../Views/usuarios/dashboard.php" class="btn blue">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>
                
                <!-- Mensajes de Error -->
                <?php if(isset($_SESSION['error'])): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel red lighten-4">
                            <span class="red-text text-darken-4">
                                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['error']); endif; ?>

                <!-- Filtros -->
                <div class="row">
                    <form method="GET" class="col s12" id="filterForm">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="fas fa-warehouse prefix"></i>
                                <input type="text" id="almacen" name="almacen" value="<?= htmlspecialchars($_GET['almacen'] ?? '') ?>">
                                <label for="almacen">Filtrar por almacén</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-tags prefix"></i>
                                <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($_GET['categoria'] ?? '') ?>">
                                <label for="categoria">Filtrar por categoría</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-truck prefix"></i>
                                <input type="text" id="proveedor" name="proveedor" value="<?= htmlspecialchars($_GET['proveedor'] ?? '') ?>">
                                <label for="proveedor">Filtrar por proveedor</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 right-align">
                                <button type="submit" class="btn blue">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <button type="button" id="clearFilters" class="btn grey">
                                    <i class="fas fa-eraser"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Estadísticas Rápidas -->
                <?php if (!empty($productos)): 
                    $stats = [
                        'total' => count($productos),
                        'sin_stock' => 0,
                        'bajo_stock' => 0,
                        'normal' => 0,
                        'exceso' => 0
                    ];
                    
                    foreach ($productos as $producto) {
                        $cantidad = (int)$producto['cantidad'];
                        $min = (int)$producto['stock_minimo'];
                        $max = (int)$producto['stock_maximo'];
                        
                        if ($cantidad <= 0) $stats['sin_stock']++;
                        elseif ($cantidad < $min) $stats['bajo_stock']++;
                        elseif ($cantidad > $max) $stats['exceso']++;
                        else $stats['normal']++;
                    }
                ?>
                <div class="row">
                    <div class="col s6 m3">
                        <div class="stats-card blue lighten-4 center">
                            <div class="stat-number blue-text"><?= $stats['total'] ?></div>
                            <div class="stat-label">Total Productos</div>
                        </div>
                    </div>
                    <div class="col s6 m3">
                        <div class="stats-card green lighten-4 center">
                            <div class="stat-number green-text"><?= $stats['normal'] ?></div>
                            <div class="stat-label">Stock Normal</div>
                        </div>
                    </div>
                    <div class="col s6 m3">
                        <div class="stats-card orange lighten-4 center">
                            <div class="stat-number orange-text"><?= $stats['bajo_stock'] ?></div>
                            <div class="stat-label">Stock Bajo</div>
                        </div>
                    </div>
                    <div class="col s6 m3">
                        <div class="stats-card red lighten-4 center">
                            <div class="stat-number red-text"><?= $stats['sin_stock'] ?></div>
                            <div class="stat-label">Sin Stock</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Información de Resultados -->
                <div class="row">
                    <div class="col s12 m6">
                        <p>
                            <strong><?= $totalProductos ?></strong> productos encontrados
                            <?php
                            // Build filter description
                            $filters = [];
                            if (!empty($_GET['almacen'])) {
                                $filters[] = "Almacén: " . htmlspecialchars($_GET['almacen']);
                            }
                            if (!empty($_GET['categoria'])) {
                                $filters[] = "Categoría: " . htmlspecialchars($_GET['categoria']);
                            }
                            if (!empty($_GET['proveedor'])) {
                                $filters[] = "Proveedor: " . htmlspecialchars($_GET['proveedor']);
                            }

                            if (!empty($filters)) {
                                echo " | Filtros: " . implode(", ", $filters);
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn grey" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if (!empty($productos)): ?>
                    <!-- Tabla de Resultados -->
                    <div class="table-responsive">
                        <table class="striped highlight responsive-table">
                            <thead>
                                <tr>
                                    <th class="sortable">Producto <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Almacén <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Cantidad <i class="fas fa-sort"></i></th>
                                    <th>Stock Mín.</th>
                                    <th>Stock Máx.</th>
                                    <th class="sortable">Categoría <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Proveedor <i class="fas fa-sort"></i></th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): 
                                    $cantidad = (int)$producto['cantidad'];
                                    $min = (int)$producto['stock_minimo'];
                                    $max = (int)$producto['stock_maximo'];

                                    $status_class = "stock-normal";
                                    $status_text = "Normal";

                                    if ($cantidad <= 0) {
                                        $status_class = "stock-danger";
                                        $status_text = "Sin Stock";
                                    } elseif ($cantidad < $min) {
                                        $status_class = "stock-warning";
                                        $status_text = "Bajo";
                                    } elseif ($cantidad > $max) {
                                        $status_class = "stock-overflow";
                                        $status_text = "Exceso";
                                    }
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($producto['producto'] ?? $producto['nombre'] ?? 'N/A') ?></strong></td>
                                        <td><?= htmlspecialchars($producto['almacen'] ?? 'N/A') ?></td>
                                        <td><?= $cantidad ?></td>
                                        <td><?= $min ?></td>
                                        <td><?= $max ?></td>
                                        <td><?= htmlspecialchars($producto['categoria'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($producto['proveedor'] ?? 'N/A') ?></td>
                                        <td><span class="stock-badge <?= $status_class ?>"><?= $status_text ?></span></td>
                                        <td>
                                            <?php if (isset($producto['id'])): ?>
                                                <a href="../../Controller/stock/ajustarStockController.php?id=<?= $producto['id'] ?>" class="btn-small blue">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/productos/verDetalleProductoController.php?id=<?= $producto['id'] ?>" class="btn-small green">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="pagination">
                        <div class="page-info">
                            Mostrando <strong><?= count($productos) ?></strong> de <strong><?= $totalProductos ?></strong> productos
                        </div>
                        <div class="page-controls">
                            <div class="page-item disabled"><i class="fas fa-chevron-left"></i></div>
                            <div class="page-item active">1</div>
                            <div class="page-item disabled"><i class="fas fa-chevron-right"></i></div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Estado Vacío -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h5>No se encontraron productos</h5>
                        <p>No hay productos que coincidan con los criterios de búsqueda. Intente con diferentes parámetros o añada productos al inventario.</p>
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn blue">
                            <i class="fas fa-plus"></i> Agregar Nuevo Producto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/reporteStock.js"></script>

</body>
</html>