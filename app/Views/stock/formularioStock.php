<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/formularioStock.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle warehouse card selection
            const warehouseCards = document.querySelectorAll('.warehouse-card');
            const warehouseRadios = document.querySelectorAll('.warehouse-radio');

            warehouseCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Find the radio input inside this card
                    const radio = this.querySelector('.warehouse-radio');

                    // Check the radio
                    radio.checked = true;

                    // Remove selected class from all cards
                    warehouseCards.forEach(c => c.classList.remove('selected'));

                    // Add selected class to clicked card
                    this.classList.add('selected');
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="header-content">
                        <h1>Ver Inventario</h1>
                        <p>Consulta y gestiona el stock de productos por almacén</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="../../Controller/stock/ajustarStockController.php" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="../../Controller/stock/verInventarioController.php" method="post">
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-building"></i> Seleccionar Almacén
                        </h2>

                        <div class="warehouse-selector">
                            <?php foreach ($almacenes as $index => $almacen): ?>
                                <label class="warehouse-card <?php echo $index ===
                                0
                                    ? "selected"
                                    : ""; ?>">
                                    <input type="radio"
                                           name="id_almacen"
                                           value="<?php echo htmlspecialchars(
                                               $almacen["id_almacen"]
                                           ); ?>"
                                           class="warehouse-radio"
                                           <?php echo $index === 0
                                               ? "checked"
                                               : ""; ?>>

                                    <div class="warehouse-icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <div class="warehouse-name">
                                        <?php echo htmlspecialchars(
                                            $almacen["nombre"]
                                        ); ?>
                                    </div>
                                    <div class="warehouse-location">
                                        <?php echo htmlspecialchars(
                                            $almacen["ubicacion"] ??
                                                "Sin ubicación especificada"
                                        ); ?>
                                    </div>

                                    <?php if (
                                        isset($almacen["productos"]) &&
                                        isset($almacen["alerta_stock"])
                                    ): ?>
                                        <div class="warehouse-stats">
                                            <div class="warehouse-stat">
                                                <span>Productos:</span>
                                                <span class="stat-value"><?php echo $almacen[
                                                    "productos"
                                                ]; ?></span>
                                            </div>
                                            <div class="warehouse-stat">
                                                <span>Alertas:</span>
                                                <span class="stat-value"><?php echo $almacen[
                                                    "alerta_stock"
                                                ]; ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="ver_inventario" class="btn btn-primary">
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
                        <div class="summary-value">128</div>
                        <div class="summary-label">Total de Productos</div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="summary-value">12</div>
                        <div class="summary-label">Productos con Stock Bajo</div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="summary-value"><?php echo count(
                            $almacenes
                        ); ?></div>
                        <div class="summary-label">Almacenes Disponibles</div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="summary-value">45</div>
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
</body>
</html>
