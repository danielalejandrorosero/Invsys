<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas de Stock Bajo | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../../../public/css/alertaStock.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const current = parseInt(bar.getAttribute('data-current'));
                const min = parseInt(bar.getAttribute('data-min'));
                const percentage = (current / min) * 100;
                bar.style.width = Math.min(percentage, 100) + '%';
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
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="header-content">
                        <h1>Alertas de Stock Bajo</h1>
                        <p>Productos que requieren atención inmediata</p>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="../../Controller/stock/ajustarStockController.php" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <a href="../../Controller/stock/verInventarioController.php" class="btn btn-primary">
                        <i class="fas fa-warehouse"></i> Ver Inventario
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="card-body">
                <?php if (!empty($productosBajoStock)): ?>
                    <div class="alerts-list">
                        <?php foreach ($productosBajoStock as $producto):
                            $isCritical =
                                isset(
                                    $producto["cantidad_disponible"],
                                    $producto["stock_minimo"]
                                ) &&
                                $producto["cantidad_disponible"] <
                                    $producto["stock_minimo"] * 0.5; ?>
                            <div class="alert-item <?php echo $isCritical
                                ? "critical"
                                : ""; ?>">
                                <div class="product-name">
                                    <i class="<?php echo $isCritical
                                        ? "fas fa-radiation"
                                        : "fas fa-exclamation-triangle"; ?>"></i>
                                    <?php echo htmlspecialchars(
                                        $producto["nombre"]
                                    ); ?>
                                </div>

                                <div class="stock-details">
                                    <div class="stock-box">
                                        <div class="stock-value <?php echo $isCritical
                                            ? "critical"
                                            : "low"; ?>">
                                            <?php echo htmlspecialchars(
                                                $producto["cantidad_disponible"]
                                            ); ?>
                                        </div>
                                        <div class="stock-label">Disponible</div>
                                    </div>

                                    <div class="stock-box">
                                        <div class="stock-value">
                                            <?php echo htmlspecialchars(
                                                $producto["stock_minimo"]
                                            ); ?>
                                        </div>
                                        <div class="stock-label">Mínimo</div>
                                    </div>
                                </div>

                                <div class="progress-bar">
                                    <div class="progress-fill"
                                         data-current="<?php echo $producto[
                                             "cantidad_disponible"
                                         ]; ?>"
                                         data-min="<?php echo $producto[
                                             "stock_minimo"
                                         ]; ?>">
                                    </div>
                                </div>

                                <div class="alert-actions">
                                    <a href="../../Controller/stock/ajustarStockController.php?id_producto=<?php echo $producto[
                                        "id_producto"
                                    ]; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Ajustar
                                    </a>
                                    <a href="../../Controller/productos/verProductoController.php?id=<?php echo $producto[
                                        "id_producto"
                                    ]; ?>" class="btn btn-secondary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </div>
                            </div>
                        <?php
                        endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h3>¡No hay alertas de stock bajo!</h3>
                        <p>Todos los productos tienen niveles de stock adecuados. No hay productos que requieran atención en este momento.</p>
                        <a href="../../Controller/stock/verInventarioController.php" class="btn btn-primary">
                            <i class="fas fa-warehouse"></i> Ver Inventario Completo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
