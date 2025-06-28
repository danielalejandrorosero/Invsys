<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../../public/index.php");
    exit();
}

// Inicializar el modelo
$stock = new Stock($conn);

// Obtener productos con bajo stock
$productosBajoStock = $stock->obtenerProductosBajoStock();

// Datos del usuario
$nombreUsuario = $_SESSION["nombreUsuario"] ?? "Nombre del Usuario";
$nivel_usuario = $_SESSION["nivel_usuario"] ?? "Nivel del Usuario";
$nombreArchivo = !empty($_SESSION["rutaImagen"]) 
    ? basename($_SESSION["rutaImagen"]) 
    : "default-avatar.png";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertas de Stock Bajo | InvSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/alertaStock.css">
</head>
<body>
    <div class="modal-container" id="alertModal">
        <div class="alert-modal">
            <div class="modal-header">
                <h2>
                    <i class="fas fa-exclamation-triangle"></i>
                    Alertas de Stock Bajo
                </h2>
                <button class="close-btn" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-content">
                <?php if (!empty($productosBajoStock)): ?>
                    <div class="alert-summary">
                        <h4><i class="fas fa-info-circle"></i> Resumen de Alertas</h4>
                        <p>Los siguientes productos requieren atención inmediata debido a su bajo nivel de stock.</p>
                        
                        <div class="summary-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($productosBajoStock); ?></div>
                                <div class="stat-label">Productos con Stock Bajo</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">
                                    <?php 
                                    $criticos = 0;
                                    foreach ($productosBajoStock as $producto) {
                                        if ($producto['cantidad_disponible'] < $producto['stock_minimo'] * 0.5) {
                                            $criticos++;
                                        }
                                    }
                                    echo $criticos;
                                    ?>
                                </div>
                                <div class="stat-label">Críticos</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-list">
                        <?php foreach ($productosBajoStock as $producto): 
                            $isCritical = $producto['cantidad_disponible'] < $producto['stock_minimo'] * 0.5;
                            $percentage = ($producto['cantidad_disponible'] / $producto['stock_minimo']) * 100;
                        ?>
                            <div class="product-item <?php echo $isCritical ? 'critical' : ''; ?>">
                                <div class="product-info">
                                    <div class="product-name">
                                        <i class="<?php echo $isCritical ? 'fas fa-radiation' : 'fas fa-exclamation-triangle'; ?>"></i>
                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                    </div>
                                    
                                    <div class="product-stock">
                                        <div class="stock-item">
                                            <div class="stock-value <?php echo $isCritical ? 'critical' : 'low'; ?>">
                                                <?php echo htmlspecialchars($producto['cantidad_disponible']); ?>
                                            </div>
                                            <div class="stock-label">Disponible</div>
                                        </div>
                                        
                                        <div class="stock-item">
                                            <div class="stock-value">
                                                <?php echo htmlspecialchars($producto['stock_minimo']); ?>
                                            </div>
                                            <div class="stock-label">Mínimo</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill <?php echo $isCritical ? 'critical' : 'low'; ?>" 
                                             style="width: <?php echo min($percentage, 100); ?>%"></div>
                                    </div>
                                    <div class="progress-text"><?php echo round($percentage, 1); ?>% del mínimo</div>
                                </div>
                                
                                <div class="product-actions">
                                    <a href="../../Controller/stock/ajustarStockController.php?id_producto=<?php echo $producto['id_producto']; ?>" 
                                       class="action-btn btn-adjust">
                                        <i class="fas fa-edit"></i> Ajustar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="empty-title">¡No hay alertas de stock bajo!</div>
                        <div class="empty-text">
                            Todos los productos tienen niveles de stock adecuados. 
                            No hay productos que requieran atención en este momento.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="modal-footer">
                <div class="alert-count">
                    <i class="fas fa-bell"></i>
                    <?php echo count($productosBajoStock); ?> alerta(s)
                </div>
                
                <div class="footer-actions">
                    <a href="../../Controller/stock/ajustarStockController.php" class="btn-primary">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <a href="../../Views/usuarios/dashboard.php" class="btn-secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/alertaStock.js"></script>
</body>
</html>
