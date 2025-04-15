<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/reporteStock.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sort indicators in table headers
            const tableHeaders = document.querySelectorAll('.sortable');
            tableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    // Remove active class from all headers
                    tableHeaders.forEach(h => {
                        h.classList.remove('active-asc', 'active-desc');
                    });

                    // Toggle sort direction for clicked header
                    if (this.classList.contains('active-asc')) {
                        this.classList.remove('active-asc');
                        this.classList.add('active-desc');
                        this.querySelector('i').className = 'fas fa-sort-down';
                    } else if (this.classList.contains('active-desc')) {
                        this.classList.remove('active-desc');
                        this.querySelector('i').className = 'fas fa-sort';
                    } else {
                        this.classList.add('active-asc');
                        this.querySelector('i').className = 'fas fa-sort-up';
                    }

                    // Here you would implement the actual sorting logic
                    // This would typically involve an AJAX call or form submission
                });
            });

            // Clear filters button
            const clearBtn = document.getElementById('clearFilters');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-form input, .filter-form select').forEach(input => {
                        input.value = '';
                    });
                    document.getElementById('filterForm').submit();
                });
            }

            // Function to apply stock level badges
            function applyStockBadges() {
                document.querySelectorAll('.stock-cell').forEach(cell => {
                    const quantity = parseInt(cell.getAttribute('data-quantity'));
                    const min = parseInt(cell.getAttribute('data-min'));
                    const max = parseInt(cell.getAttribute('data-max'));

                    let badgeClass = 'stock-normal';
                    let status = 'Normal';

                    if (quantity <= 0) {
                        badgeClass = 'stock-danger';
                        status = 'Sin Stock';
                    } else if (quantity < min) {
                        badgeClass = 'stock-warning';
                        status = 'Bajo';
                    } else if (quantity > max) {
                        badgeClass = 'stock-overflow';
                        status = 'Exceso';
                    }

                    const badge = `<span class="stock-badge ${badgeClass}">${status}</span>`;
                    cell.innerHTML = `${quantity} ${badge}`;
                });
            }

            // Initialize the badges
            applyStockBadges();
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="header-content">
                        <h1>Reporte de Stock</h1>
                        <p>Análisis detallado del inventario actual por producto, almacén y categoría</p>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn btn-light" id="printReport">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </button>
                    <button class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="fas fa-filter"></i> Filtrar Resultados
                    </div>
                    <form method="GET" class="filter-form" id="filterForm">
                        <div class="form-group">
                            <label for="almacen" class="form-label">Almacén</label>
                            <div class="input-group">
                                <i class="fas fa-warehouse input-icon"></i>
                                <input type="text" id="almacen" name="almacen" class="form-control" placeholder="Filtrar por almacén" value="<?= isset(
                                    $_GET["almacen"]
                                )
                                    ? htmlspecialchars($_GET["almacen"])
                                    : "" ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="categoria" class="form-label">Categoría</label>
                            <div class="input-group">
                                <i class="fas fa-tags input-icon"></i>
                                <input type="text" id="categoria" name="categoria" class="form-control" placeholder="Filtrar por categoría" value="<?= isset(
                                    $_GET["categoria"]
                                )
                                    ? htmlspecialchars($_GET["categoria"])
                                    : "" ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <div class="input-group">
                                <i class="fas fa-truck input-icon"></i>
                                <input type="text" id="proveedor" name="proveedor" class="form-control" placeholder="Filtrar por proveedor" value="<?= isset(
                                    $_GET["proveedor"]
                                )
                                    ? htmlspecialchars($_GET["proveedor"])
                                    : "" ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <button type="button" id="clearFilters" class="btn btn-light">
                                    <i class="fas fa-eraser"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Controls -->
                <div class="table-controls">
                    <div class="table-info">
                        <?php
                        $total_items = $resultado ? $resultado->num_rows : 0;
                        echo "<strong>{$total_items}</strong> productos encontrados";

                        // Build filter description
                        $filters = [];
                        if (!empty($_GET["almacen"])) {
                            $filters[] =
                                "Almacén: " .
                                htmlspecialchars($_GET["almacen"]);
                        }
                        if (!empty($_GET["categoria"])) {
                            $filters[] =
                                "Categoría: " .
                                htmlspecialchars($_GET["categoria"]);
                        }
                        if (!empty($_GET["proveedor"])) {
                            $filters[] =
                                "Proveedor: " .
                                htmlspecialchars($_GET["proveedor"]);
                        }

                        if (!empty($filters)) {
                            echo " | Filtros: " . implode(", ", $filters);
                        }
                        ?>
                    </div>
                    <div class="table-actions">
                        <button class="btn btn-light">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <!-- Table Results -->
                    <div class="table-responsive">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th class="sortable">Producto <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Almacén <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Cantidad <i class="fas fa-sort"></i></th>
                                    <th>Stock Mínimo</th>
                                    <th>Stock Máximo</th>
                                    <th class="sortable">Categoría <i class="fas fa-sort"></i></th>
                                    <th class="sortable">Proveedor <i class="fas fa-sort"></i></th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado->fetch_assoc()):

                                    // Determine stock status
                                    $quantity = (int) $row["cantidad"];
                                    $min = (int) $row["stock_minimo"];
                                    $max = (int) $row["stock_maximo"];

                                    $status_class = "stock-normal";
                                    $status_text = "Normal";

                                    if ($quantity <= 0) {
                                        $status_class = "stock-danger";
                                        $status_text = "Sin Stock";
                                    } elseif ($quantity < $min) {
                                        $status_class = "stock-warning";
                                        $status_text = "Bajo";
                                    } elseif ($quantity > $max) {
                                        $status_class = "stock-overflow";
                                        $status_text = "Exceso";
                                    }
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars(
                                            $row["producto"]
                                        ) ?></strong></td>
                                        <td><?= htmlspecialchars(
                                            $row["almacen"]
                                        ) ?></td>
                                        <td class="stock-cell"
                                            data-quantity="<?= $quantity ?>"
                                            data-min="<?= $min ?>"
                                            data-max="<?= $max ?>">
                                            <?= $quantity ?>
                                        </td>
                                        <td><?= htmlspecialchars(
                                            $row["stock_minimo"]
                                        ) ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["stock_maximo"]
                                        ) ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["categoria"]
                                        ) ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["proveedor"]
                                        ) ?></td>
                                        <td><span class="stock-badge <?= $status_class ?>"><?= $status_text ?></span></td>
                                    </tr>
                                <?php
                                endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="page-info">
                            Mostrando <strong>1-<?= min(
                                $total_items,
                                10
                            ) ?></strong> de <strong><?= $total_items ?></strong> productos
                        </div>
                        <div class="page-controls">
                            <div class="page-item disabled"><i class="fas fa-chevron-left"></i></div>
                            <div class="page-item active">1</div>
                            <?php if ($total_items > 10): ?>
                                <div class="page-item">2</div>
                            <?php endif; ?>
                            <?php if ($total_items > 20): ?>
                                <div class="page-item">3</div>
                            <?php endif; ?>
                            <?php if ($total_items > 30): ?>
                                <div class="page-item">...</div>
                                <div class="page-item"><?= ceil(
                                    $total_items / 10
                                ) ?></div>
                            <?php endif; ?>
                            <div class="page-item <?= $total_items <= 10
                                ? "disabled"
                                : "" ?>"><i class="fas fa-chevron-right"></i></div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron resultados</h3>
                        <p>No hay productos que coincidan con los criterios de búsqueda. Intente con diferentes parámetros o añada productos al inventario.</p>
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
