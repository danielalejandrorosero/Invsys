<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas de Stock Bajo | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        .header-content h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .header-content p {
            margin: 0;
            color: #757575;
        }
        .header-actions a {
            margin-left: 10px;
        }
        .alert-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #fff3e0;
        }
        .alert-item.critical {
            background-color: #ffebee;
        }
        .product-name {
            display: flex;
            align-items: center;
            font-weight: bold;
        }
        .product-name i {
            margin-right: 10px;
        }
        .stock-details {
            display: flex;
            align-items: center;
        }
        .stock-box {
            text-align: center;
            margin-right: 20px;
        }
        .stock-value {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .stock-value.low {
            color: #ff9800;
        }
        .stock-value.critical {
            color: #f44336;
        }
        .stock-label {
            font-size: 0.875rem;
            color: #757575;
        }
        .progress-bar {
            flex: 1;
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 20px;
        }
        .progress-fill {
            height: 100%;
            background-color: #4caf50;
        }
        .alert-actions a {
            margin-left: 10px;
        }
        .empty-state {
            text-align: center;
            margin-top: 50px;
        }
        .empty-state i {
            font-size: 3rem;
            color: #4caf50;
            margin-bottom: 20px;
        }
    </style>
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
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="header-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="header-content">
                            <h1>Alertas de Stock Bajo</h1>
                            <p>Productos que requieren atención inmediata</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 right-align">
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn yellow darken-2">
                            <i class="fas fa-edit"></i> Ajustar Stock
                        </a>
                        <a href="../../Controller/stock/verInventarioController.php" class="btn blue">
                            <i class="fas fa-warehouse"></i> Ver Inventario
                        </a>
                        <a href="index.php" class="btn grey">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <?php if (!empty($productosBajoStock)): ?>
                            <div class="alerts-list">
                                <?php foreach (
                                    $productosBajoStock
                                    as $producto
                                ):
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
                                                        $producto[
                                                            "cantidad_disponible"
                                                        ]
                                                    ); ?>
                                                </div>
                                                <div class="stock-label">Disponible</div>
                                            </div>

                                            <div class="stock-box">
                                                <div class="stock-value">
                                                    <?php echo htmlspecialchars(
                                                        $producto[
                                                            "stock_minimo"
                                                        ]
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
                                            ]; ?>" class="btn yellow darken-2">
                                                <i class="fas fa-edit"></i> Ajustar
                                            </a>
                                            <a href="../../Controller/productos/verProductoController.php?id=<?php echo $producto[
                                                "id_producto"
                                            ]; ?>" class="btn grey">
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
                                <a href="../../Controller/stock/verInventarioController.php" class="btn blue">
                                    <i class="fas fa-warehouse"></i> Ver Inventario Completo
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
