<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .metrics-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .metric-card {
            flex: 1;
            margin: 0 10px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
        }
        .metric-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .metric-icon.entries {
            color: #4caf50;
        }
        .metric-icon.exits {
            color: #f44336;
        }
        .metric-icon.transfers {
            color: #2196f3;
        }
        .metric-icon.adjustments {
            color: #ff9800;
        }
        .metric-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .filter-section {
            margin-bottom: 20px;
        }
        .filter-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-group i {
            margin-right: 10px;
        }
        .date-range {
            display: flex;
            justify-content: space-between;
        }
        .date-range .input-group {
            flex: 1;
            margin-right: 10px;
        }
        .date-range .input-group:last-child {
            margin-right: 0;
        }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-info {
            font-size: 1rem;
        }
        .table-actions {
            display: flex;
            align-items: center;
        }
        .table-actions button {
            margin-left: 10px;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .page-buttons {
            display: flex;
            align-items: center;
        }
        .page-btn {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .page-btn.active {
            background-color: #2196f3;
            color: #fff;
        }
        .page-btn.disabled {
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
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
        }
        .badge-success {
            background-color: #4caf50;
        }
        .badge-danger {
            background-color: #f44336;
        }
        .badge-info {
            background-color: #2196f3;
        }
        .badge-warning {
            background-color: #ff9800;
        }
        .quantity-positive {
            color: #4caf50;
        }
        .quantity-negative {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-exchange-alt"></i> Historial de Movimientos</h4>
                        <p>Visualiza y analiza todos los movimientos de inventario</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn blue">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <!-- Summary Metrics -->
                <div class="row metrics-grid">
                    <div class="col s12 m3">
                        <div class="metric-card">
                            <div class="metric-icon entries">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">125</div>
                                <div class="metric-label">Entradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="metric-card">
                            <div class="metric-icon exits">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">98</div>
                                <div class="metric-label">Salidas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="metric-card">
                            <div class="metric-icon transfers">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">43</div>
                                <div class="metric-label">Transferencias</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="metric-card">
                            <div class="metric-icon adjustments">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">21</div>
                                <div class="metric-label">Ajustes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row filter-section">
                    <form method="GET" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="fas fa-warehouse prefix"></i>
                                <input type="text" id="almacen" name="almacen" value="<?= isset(
                                    $_GET["almacen"]
                                )
                                    ? htmlspecialchars($_GET["almacen"])
                                    : "" ?>">
                                <label for="almacen">Buscar por almacén</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-box prefix"></i>
                                <input type="text" id="producto" name="producto" value="<?= isset(
                                    $_GET["producto"]
                                )
                                    ? htmlspecialchars($_GET["producto"])
                                    : "" ?>">
                                <label for="producto">Buscar por producto</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-tags prefix"></i>
                                <select id="tipo" name="tipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="entrada" <?= isset(
                                        $_GET["tipo"]
                                    ) && $_GET["tipo"] === "entrada"
                                        ? "selected"
                                        : "" ?>>Entrada</option>
                                    <option value="salida" <?= isset(
                                        $_GET["tipo"]
                                    ) && $_GET["tipo"] === "salida"
                                        ? "selected"
                                        : "" ?>>Salida</option>
                                    <option value="transferencia" <?= isset(
                                        $_GET["tipo"]
                                    ) && $_GET["tipo"] === "transferencia"
                                        ? "selected"
                                        : "" ?>>Transferencia</option>
                                    <option value="ajuste" <?= isset(
                                        $_GET["tipo"]
                                    ) && $_GET["tipo"] === "ajuste"
                                        ? "selected"
                                        : "" ?>>Ajuste</option>
                                </select>
                                <label for="tipo">Tipo de Movimiento</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-calendar prefix"></i>
                                <input type="date" name="fecha_desde">
                                <label for="fecha_desde">Desde</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="fas fa-calendar prefix"></i>
                                <input type="date" name="fecha_hasta">
                                <label for="fecha_hasta">Hasta</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 right-align">
                                <button type="submit" class="btn blue">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Controls -->
                <div class="row table-controls">
                    <div class="col s12 m6">
                        <p><strong><?php echo $resultados
                            ? $resultados->num_rows
                            : 0; ?></strong> resultados encontrados</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn grey" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if ($resultados && $resultados->num_rows > 0): ?>
                    <!-- Table Results -->
                    <div class="table-responsive">
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Almacén Origen</th>
                                    <th>Almacén Destino</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultados->fetch_assoc()):

                                    // Determine badge class based on movement type
                                    $badgeClass = "badge-warning";
                                    $movementType = strtolower(
                                        $row["tipo_movimiento"]
                                    );

                                    if (
                                        strpos($movementType, "entrada") !==
                                        false
                                    ) {
                                        $badgeClass = "badge-success";
                                    } elseif (
                                        strpos($movementType, "salida") !==
                                        false
                                    ) {
                                        $badgeClass = "badge-danger";
                                    } elseif (
                                        strpos(
                                            $movementType,
                                            "transferencia"
                                        ) !== false
                                    ) {
                                        $badgeClass = "badge-info";
                                    }

                                    // Determine quantity class
                                    $quantityClass = "";
                                    $quantity = (int) $row["cantidad"];
                                    if ($quantity > 0) {
                                        $quantityClass = "quantity-positive";
                                    } elseif ($quantity < 0) {
                                        $quantityClass = "quantity-negative";
                                    }

                                    // Format date
                                    $date = new DateTime(
                                        $row["fecha_movimiento"]
                                    );
                                    $formattedDate = $date->format("d/m/Y H:i");
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars(
                                            $row["producto"]
                                        ) ?></strong></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(
    $row["tipo_movimiento"]
) ?></span></td>
                                        <td class="<?= $quantityClass ?>"><?= htmlspecialchars(
    $row["cantidad"]
) ?></td>
                                        <td><?= $formattedDate ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["almacen_origen"] ?: "-"
                                        ) ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["almacen_destino"] ?: "-"
                                        ) ?></td>
                                        <td><?= htmlspecialchars(
                                            $row["usuario"]
                                        ) ?></td>
                                    </tr>
                                <?php
                                endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        <div class="page-info">
                            Mostrando 1-<?= min(
                                $resultados->num_rows,
                                20
                            ) ?> de <?= $resultados->num_rows ?> resultados
                        </div>
                        <div class="page-buttons">
                            <button class="page-btn disabled"><i class="fas fa-angle-left"></i></button>
                            <button class="page-btn active">1</button>
                            <?php if ($resultados->num_rows > 20): ?>
                                <button class="page-btn">2</button>
                            <?php endif; ?>
                            <?php if ($resultados->num_rows > 40): ?>
                                <button class="page-btn">3</button>
                            <?php endif; ?>
                            <?php if ($resultados->num_rows > 60): ?>
                                <span>...</span>
                                <button class="page-btn"><?= ceil(
                                    $resultados->num_rows / 20
                                ) ?></button>
                            <?php endif; ?>
                            <button class="page-btn <?= $resultados->num_rows <=
                            20
                                ? "disabled"
                                : "" ?>"><i class="fas fa-angle-right"></i></button>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron movimientos</h3>
                        <p>No hay registros de movimientos que coincidan con los criterios de búsqueda especificados. Intente con otros filtros o realice un nuevo movimiento.</p>
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn blue">
                            <i class="fas fa-plus-circle"></i> Nuevo Movimiento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
