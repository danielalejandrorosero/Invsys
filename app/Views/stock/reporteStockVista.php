<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .steps-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .step {
            text-align: center;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .step.active .step-circle {
            background-color: #2196f3;
            color: #fff;
        }
        .step-label {
            font-size: 0.875rem;
            color: #757575;
        }
        .search-product {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-product i {
            margin-right: 10px;
        }
        .product-select {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .product-option {
            flex: 1 1 calc(50% - 10px);
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .product-option.selected {
            background-color: #e0f7fa;
        }
        .product-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        .product-details {
            flex: 1;
        }
        .product-name {
            font-weight: bold;
        }
        .product-info {
            color: #757575;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stock-badge {
            padding: 2px 5px;
            border-radius: 4px;
            color: #fff;
        }
        .stock-normal {
            background-color: #4caf50;
        }
        .stock-warning {
            background-color: #ff9800;
        }
        .stock-danger {
            background-color: #f44336;
        }
        .stock-overflow {
            background-color: #2196f3;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .page-controls {
            display: flex;
            align-items: center;
        }
        .page-item {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .page-item.active {
            background-color: #2196f3;
            color: #fff;
        }
        .page-item.disabled {
            color: #bdbdbd;
            cursor: not-allowed;
        }
        .empty-state {
            text-align: center;
            margin-top: 50px;
        }
        .empty-state i {
            font-size: 3rem;
            color: #bdbdbd;
            margin-bottom: 20px;
        }
    </style>
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
            <div class="card-content">
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-chart-bar"></i> Reporte de Stock</h4>
                        <p>Análisis detallado del inventario actual por producto, almacén y categoría</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn grey" id="printReport">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                        <button class="btn green">
                            <i class="fas fa-file-excel"></i> Exportar Excel
                        </button>
                        <button class="btn red">
                            <i class="fas fa-file-pdf"></i> Exportar PDF
                        </button>
                        <a href="../../Views/usuarios/dashboard.php" class="btn blue">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row">
                    <form method="GET" class="col s12" id="filterForm">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="fas fa-warehouse prefix"></i>
                                <input type="text" id="almacen" name="almacen" value="<?= isset(
                                    $_GET["almacen"]
                                )
                                    ? htmlspecialchars($_GET["almacen"])
                                    : "" ?>">
                                <label for="almacen">Filtrar por almacén</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-tags prefix"></i>
                                <input type="text" id="categoria" name="categoria" value="<?= isset(
                                    $_GET["categoria"]
                                )
                                    ? htmlspecialchars($_GET["categoria"])
                                    : "" ?>">
                                <label for="categoria">Filtrar por categoría</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-truck prefix"></i>
                                <input type="text" id="proveedor" name="proveedor" value="<?= isset(
                                    $_GET["proveedor"]
                                )
                                    ? htmlspecialchars($_GET["proveedor"])
                                    : "" ?>">
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

                <!-- Table Controls -->
                <div class="row">
                    <div class="col s12 m6">
                        <p>
                            <?php
                            $total_items = $resultado
                                ? $resultado->num_rows
                                : 0;
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
                        </p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn grey">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <!-- Table Results -->
                    <div class="table-responsive">
                        <table class="highlight">
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
                        <a href="../../Controller/productos/agregarProductoController.php" class="btn blue">
                            <i class="fas fa-plus"></i> Agregar Nuevo Producto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
