<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/verInventario.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Table search
            const searchInput = document.getElementById('tableSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('.inventory-table tbody tr');

                    tableRows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();
                        row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Initialize stock level bars
            const stockBars = document.querySelectorAll('.stock-bar');
            stockBars.forEach(bar => {
                const value = parseInt(bar.getAttribute('data-value'));
                const max = parseInt(bar.getAttribute('data-max')) || 100;
                const percentage = (value / max) * 100;
                bar.style.width = `${Math.min(percentage, 100)}%`;

                if (percentage < 30) {
                    bar.classList.add('stock-low');
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
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="header-content">
                        <h1>Inventario del Almacén</h1>
                        <p>Control y gestión de productos disponibles en el inventario</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="../../Controller/stock/ajustarStockController.php" class="btn btn-success">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <a href="../../Controller/stock/movimientoStockController.php" class="btn btn-info">
                        <i class="fas fa-exchange-alt"></i> Movimientos
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value"><?php echo count(
                                $inventario
                            ) ?? 0; ?></div>
                            <div class="stat-label">Total de productos</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">
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
                            </div>
                            <div class="stat-label">Productos con stock adecuado</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">
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
                            </div>
                            <div class="stat-label">Productos con stock bajo</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-value">
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
                            </div>
                            <div class="stat-label">Productos sin stock</div>
                        </div>
                    </div>
                </div>

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

                <?php if (empty($inventario)): ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No hay productos en este almacén</h3>
                        <p>Actualmente no hay productos registrados en este almacén. Puede comenzar agregando productos al inventario.</p>
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> Ajustar Stock
                        </a>
                    </div>
                <?php
                    // Set default values if not present

                    // Determine stock status

                    // Generate first letter of product name for icon
                    // Set default values if not present
                    // Determine stock status
                    // Generate first letter of product name for icon
                    else: ?>
                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="inventory-table">
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

                                    $stock_minimo = $item["stock_minimo"] ?? 10;
                                    $stock_maximo =
                                        $item["stock_maximo"] ?? 100;
                                    $cantidad = $item["cantidad"];

                                    $stockStatus = "success";
                                    $stockLabel = "Adecuado";

                                    if ($cantidad <= 0) {
                                        $stockStatus = "danger";
                                        $stockLabel = "Sin Stock";
                                    } elseif ($cantidad <= $stock_minimo) {
                                        $stockStatus = "warning";
                                        $stockLabel = "Bajo";
                                    }

                                    $firstLetter = mb_substr(
                                        $item["producto"],
                                        0,
                                        1,
                                        "UTF-8"
                                    );
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="product-name">
                                                <div class="product-icon">
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
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $stockStatus; ?>">
                                                <?php echo $stockLabel; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="stock-indicator">
                                                <div class="stock-bar-container">
                                                    <div class="stock-bar"
                                                         data-value="<?php echo $cantidad; ?>"
                                                         data-max="<?php echo $stock_maximo; ?>">
                                                    </div>
                                                </div>
                                                <div class="stock-value">
                                                    <?php echo round(
                                                        ($cantidad /
                                                            $stock_maximo) *
                                                            100
                                                    ); ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="../../Controller/stock/ajustarStockController.php?id=<?php echo $item[
                                                    "id_producto"
                                                ] ??
                                                    ""; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i> Ajustar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                endforeach; ?>
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
