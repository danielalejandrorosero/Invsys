<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/movimientoStock.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="header-content">
                        <h1>Historial de Movimientos</h1>
                        <p>Visualiza y analiza todos los movimientos de inventario</p>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-file-export"></i> Exportar
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Summary Metrics -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon entries">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="metric-details">
                            <div class="metric-value">125</div>
                            <div class="metric-label">Entradas</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon exits">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="metric-details">
                            <div class="metric-value">98</div>
                            <div class="metric-label">Salidas</div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon transfers">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="metric-details">
                            <div class="metric-value">43</div>
                            <div class="metric-label">Transferencias</div>
                        </div>
                    </div>

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

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-title">
                        <i class="fas fa-filter"></i> Filtrar Movimientos
                    </div>
                    <form method="GET" class="filter-form">
                        <div class="form-group">
                            <label for="almacen" class="form-label">Almacén</label>
                            <div class="input-group">
                                <i class="fas fa-warehouse input-icon"></i>
                                <input type="text" id="almacen" name="almacen" class="form-control" placeholder="Buscar por almacén" value="<?= isset(
                                    $_GET["almacen"]
                                )
                                    ? htmlspecialchars($_GET["almacen"])
                                    : "" ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="producto" class="form-label">Producto</label>
                            <div class="input-group">
                                <i class="fas fa-box input-icon"></i>
                                <input type="text" id="producto" name="producto" class="form-control" placeholder="Buscar por producto" value="<?= isset(
                                    $_GET["producto"]
                                )
                                    ? htmlspecialchars($_GET["producto"])
                                    : "" ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tipo" class="form-label">Tipo de Movimiento</label>
                            <div class="input-group">
                                <i class="fas fa-tags input-icon"></i>
                                <select id="tipo" name="tipo" class="form-control">
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
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Fecha</label>
                            <div class="date-range">
                                <div class="input-group">
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input type="date" name="fecha_desde" class="form-control" placeholder="Desde">
                                </div>
                                <div class="input-group">
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input type="date" name="fecha_hasta" class="form-control" placeholder="Hasta">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Controls -->
                <div class="table-controls">
                    <div class="table-info">
                        <strong><?php echo $resultados
                            ? $resultados->num_rows
                            : 0; ?></strong> resultados encontrados
                    </div>
                    <div class="table-actions">
                        <button class="btn btn-light" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if ($resultados && $resultados->num_rows > 0): ?>
                    <!-- Table Results -->
                    <div class="table-responsive">
                        <table class="movements-table">
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
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Nuevo Movimiento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
