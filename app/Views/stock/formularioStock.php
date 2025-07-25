<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/formulario.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="header-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="header-content">
                            <h1>Ver Inventario</h1>
                            <p>Consulta y gestiona el stock de productos por almacén</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 right-align">
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn blue">
                            <i class="fas fa-edit"></i> Ajustar Stock
                        </a>
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <form action="../../Controller/stock/verInventarioController.php" method="post">
                            <div class="form-section">
                                <h2 class="section-title">
                                    <i class="fas fa-building"></i> Seleccionar Almacén
                                </h2>

                                <div class="warehouse-selector">
                                    <?php foreach ($almacenes as $index => $almacen): ?>
                                        <label class="warehouse-card <?php echo $index === 0 ? "selected" : ""; ?>">
                                            <input type="radio"
                                                   name="id_almacen"
                                                   value="<?php echo htmlspecialchars($almacen["id_almacen"]); ?>"
                                                   class="warehouse-radio"
                                                   <?php echo $index === 0 ? "checked" : ""; ?>>

                                            <div class="warehouse-icon">
                                                <i class="fas fa-warehouse"></i>
                                            </div>
                                            <div class="warehouse-name">
                                                <?php echo htmlspecialchars($almacen["nombre"]); ?>
                                            </div>
                                            <div class="warehouse-location">
                                                <?php echo htmlspecialchars($almacen["ubicacion"] ?? "Sin ubicación especificada"); ?>
                                            </div>

                                            <div class="warehouse-stats">
                                                <div class="warehouse-stat">
                                                    <span>Productos:</span>
                                                    <span class="stat-value"><?php echo isset($almacen["productos"]) ? $almacen["productos"] : 0; ?></span>
                                                </div>
                                                <div class="warehouse-stat">
                                                    <span>Alertas:</span>
                                                    <span class="stat-value"><?php echo isset($almacen["alerta_stock"]) ? $almacen["alerta_stock"] : 0; ?></span>
                                                </div>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" name="ver_inventario" class="btn blue">
                                        <i class="fas fa-search"></i> Ver Inventario
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Inventory Summary Section -->
                        <div class="inventory-summary">
                            <div class="summary-card">
                                <div class="summary-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="summary-value"><?php echo htmlspecialchars($productos); ?></div>
                                <div class="summary-label">Total de Productos</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="summary-value"><?php echo count($productosBajoStock); ?></div>
                                <div class="summary-label">Productos con Stock Bajo</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="summary-value"><?php echo count($almacenes); ?></div>
                                <div class="summary-label">Almacenes Disponibles</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div class="summary-value"><?php echo count($inventarioGlobal); ?></div>
                                <div class="summary-label">Movimientos Recientes</div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <h2 class="section-title" style="margin-top: 30px;">
                            <i class="fas fa-bolt"></i> Acciones Rápidas
                        </h2>

                        <div class="quick-actions">
                            <a href="../../Controller/stock/ajustarStockController.php" class="action-button">
                                <div class="action-icon action-adjust">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="action-label">Ajustar Stock</div>
                            </a>

                            <a href="../../Controller/stock/transferirStock.php" class="action-button">
                                <div class="action-icon action-transfer">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="action-label">Transferir Stock</div>
                            </a>

                            <a href="../../Controller/stock/reporteStockController.php" class="action-button">
                                <div class="action-icon action-report">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="action-label">Generar Reporte</div>
                            </a>

                            <a href="../../Controller/stock/alertaStockController.php" class="action-button">
                                <div class="action-icon action-alert">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="action-label">Ver Alertas</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/formulario.js"></script>
</body>
</html>