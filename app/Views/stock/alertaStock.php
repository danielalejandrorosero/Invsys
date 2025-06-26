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
    <title>Alertas de Stock Bajo | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }
        
        .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .alert-modal {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideIn 0.4s ease-out;
        }
        
        @keyframes slideIn {
            from { 
                transform: translateY(-50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white;
            padding: 20px 30px;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .modal-content {
            padding: 30px;
        }
        
        .alert-summary {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 5px solid #ff9800;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ff5722;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .product-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .product-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #ff9800;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .product-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .product-item.critical {
            border-left-color: #f44336;
            background: #ffebee;
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .product-stock {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        
        .stock-item {
            text-align: center;
        }
        
        .stock-value {
            font-size: 1.3rem;
            font-weight: bold;
        }
        
        .stock-value.low {
            color: #ff9800;
        }
        
        .stock-value.critical {
            color: #f44336;
        }
        
        .stock-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 2px;
        }
        
        .progress-container {
            flex: 1;
            margin: 0 20px;
        }
        
        .progress-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 5px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4caf50, #8bc34a);
            transition: width 0.5s ease;
        }
        
        .progress-fill.low {
            background: linear-gradient(90deg, #ff9800, #ffb74d);
        }
        
        .progress-fill.critical {
            background: linear-gradient(90deg, #f44336, #ef5350);
        }
        
        .progress-text {
            font-size: 0.8rem;
            color: #666;
            text-align: center;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-adjust {
            background: #2196f3;
            color: white;
        }
        
        .btn-adjust:hover {
            background: #1976d2;
            transform: translateY(-2px);
        }
        
        .btn-view {
            background: #9e9e9e;
            color: white;
        }
        
        .btn-view:hover {
            background: #757575;
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #4caf50;
            margin-bottom: 20px;
        }
        
        .empty-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }
        
        .empty-text {
            color: #666;
            margin-bottom: 20px;
        }
        
        .modal-footer {
            padding: 20px 30px;
            background: #f5f5f5;
            border-radius: 0 0 15px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-primary {
            background: #2196f3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #1976d2;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #9e9e9e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #757575;
            transform: translateY(-2px);
        }
        
        .alert-count {
            background: #ff5722;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: bold;
        }
    </style>
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
                                    <a href="../../Controller/productos/verProductoController.php?id=<?php echo $producto['id_producto']; ?>" 
                                       class="action-btn btn-view">
                                        <i class="fas fa-eye"></i> Ver
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
    <script>
        function closeModal() {
            document.getElementById('alertModal').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera de él
        document.getElementById('alertModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Auto-cerrar después de 30 segundos si no hay interacción
        setTimeout(function() {
            if (document.getElementById('alertModal').style.display !== 'none') {
                closeModal();
            }
        }, 30000);
    </script>
</body>
</html>
