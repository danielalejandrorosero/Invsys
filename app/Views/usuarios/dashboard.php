<?php


require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";
require_once __DIR__ . "/../../Models/productos/productos.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";


// Inicializar variable de sesión para la alerta de stock bajo
if (!isset($_SESSION['alerta_stock_mostrada'])) {
    $_SESSION['alerta_stock_mostrada'] = false;
}   

// Inicializar modelos una sola vez
$usuario = new Usuario($conn);
$stock = new Stock($conn);
$productos = new Productos($conn);

// Obtener datos para el dashboard en una sola sección
$productosBajoStock = $stock->obtenerProductosBajoStock();
$totalProductos = $productos->contarTotalProductos();
$transferenciaPendientes = $stock->contarTransferenciasPendientes();

// Obtener los últimos movimientos para mostrar en actividad reciente (optimizado)
$ultimosMovimientos = $stock->obtenerMovimientos(null, null, null);
$movimientosRecientes = [];
$contador = 0;

if ($ultimosMovimientos && $ultimosMovimientos->num_rows > 0) {
    while ($movimiento = $ultimosMovimientos->fetch_assoc()) {
        $movimientosRecientes[] = $movimiento;
        $contador++;
        if ($contador >= 5) break; // Limitar a 5 movimientos recientes
    }
}

// Datos del usuario (consolidados)
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
    <title>Dashboard de Gestión | InvSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/dark-mode.css">

    <link rel="stylesheet" href="../../../public/css/chatbot.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../">
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
    <meta name="description" content="Panel de control del sistema de gestión de inventario InvSys">
    <meta name="theme-color" content="#2c3e50">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
</head>

<body>
    <!-- Botón para móviles -->
    <button onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>InvSys</h2>
            <div class="user-info">
                <div class="avatar">
                    <img src="../../../public/uploads/imagenes/usuarios/<?php echo htmlspecialchars($nombreArchivo); ?>?v=<?php echo time(); ?>" alt="Avatar">
                </div>
                <div class="user-details">
                    <h3><?php echo htmlspecialchars($nombreUsuario); ?></h3>
                    <p>Nivel: <?php echo htmlspecialchars($nivel_usuario); ?></p>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <h3>Usuarios</h3>
            <ul>
                <li><a href="../../Controller/usuarios/agregarController.php"><i class="fas fa-user-plus"></i> Agregar Usuario</a></li>
                <li><a href="../../Controller/usuarios/listarUsuarios.php"><i class="fas fa-users"></i> Listar Usuarios</a></li>
                <li><a href="../../Controller/usuarios/editarUsuario.php"><i class="fas fa-user-edit"></i> Editar Usuario</a></li>
                <li><a href="../../Controller/usuarios/eliminarUsuarioController.php"><i class="fas fa-user-minus"></i> Eliminar Usuario</a></li>
            </ul>

            <h3>Inventario</h3>
            <ul>
                <li><a href="../../Controller/stock/verInventarioController.php"><i class="fas fa-boxes"></i> Ver Inventario</a></li>
                <li><a href="../../Controller/stock/ajustarStockController.php"><i class="fas fa-edit"></i> Ajustar Stock</a></li>
                <li><a href="../../Controller/stock/movimientoStockController.php"><i class="fas fa-exchange-alt"></i> Movimientos</a></li>
                <li><a href="../../Controller/stock/reporteStockController.php"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                <li><a href="../../Controller/stock/transferirStock.php"><i class="fas fa-truck"></i> Transferir Stock</a></li>
                <li><a href="../../Controller/stock/crearAlmacen.php"><i class="fas fa-truck"></i> Crear Almacen</a></li>
                <li><a href="../../Controller/stock/ListarAlmacenesController.php"><i class="fas fa-warehouse"></i> Almacenes</a></li>
                <li><a href="../../Controller/stock/productosSinAlmacenController.php"><i class="fas fa-warehouse"></i> Productos sin Almacen   </a></li>
                <li><a href="../../Controller/stock/alertaStockController.php"><i class="fas fa-exclamation-triangle"></i> Alertas de Stock</a></li>

            </ul>

            <h3>Productos</h3>
            <ul>
                <li><a href="../../Controller/productos/agregarProductoController.php"><i class="fas fa-plus-circle"></i> Agregar Producto</a></li>
                <li><a href="../../Controller/productos/agregarCategoriaController.php"><i class="fas fa-folder-plus"></i> Agregar Categoría</a></li>
                <li><a href="../../Controller/productos/ListarCategoriasController.php"><i class="fas fa-list-alt"></i> Listar Categorías</a></li>
                <li><a href="../../Controller/productos/buscarProductosController.php"><i class="fas fa-search"></i> Buscar Producto</a></li>
                <li><a href="../../Controller/productos/RestaurarProductoController.php"><i class="fas fa-trash-restore"></i> Restaurar Producto</a></li>
                <li><a href="../../Controller/productos/ListarProductosController.php"><i class="fas fa-list"></i> Listar Productos</a></li>
            </ul>

            <h3>Imágenes</h3>
            <ul>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=producto"><i class="fas fa-image"></i> Imágenes de Productos</a></li>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=usuario"><i class="fas fa-user-circle"></i> Imágenes de Usuarios</a></li>
            </ul>

            <h3>Proveedores</h3>
            <ul>
                <li><a href="../../Controller/proveedores/listarProveedores.php"><i class="fas fa-list"></i> Ver Proveedores</a></li>
                <li><a href="../../Controller/proveedores/agregarProveedor.php"><i class="fas fa-plus"></i> Nuevo Proveedor</a></li>
                <li><a href="../../Controller/proveedores/seleccionarProveedorHistorialController.php"><i class="fas fa-history"></i> Historial de Proveedores</a></li>
                <li><a href="../../Controller/proveedores/ListarProveedoresEliminadosController.php"><i class="fas fa-trash-alt"></i> Proveedores Eliminados</a></li>
            </ul>


            <h3>Sistema</h3>
            <ul>
                <li><a href="../../Controller/usuarios/cerrarSesionController.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1><i class="fas fa-tachometer-alt"></i> Panel de Control</h1>
                    <p>Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>. Última actualización: <?php echo date('d/m/Y H:i'); ?></p>
                </div>
                <div class="dark-mode-toggle" style="position: static; width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #2196f3, #1976d2); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3); transition: all 0.3s ease; border: 2px solid rgba(255, 255, 255, 0.2);" title="Cambiar modo claro/oscuro" onclick="toggleDarkMode()">
                    <i id="dark-mode-icon" class="fas fa-moon" style="font-size: 1.2rem;"></i>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="quick-actions">
            <a href="../../Controller/productos/agregarProductoController.php"><i class="fas fa-plus-circle"></i> Nuevo Producto</a>
            <a href="../../Controller/stock/ajustarStockController.php"><i class="fas fa-edit"></i> Ajustar Stock</a>
            <a href="../../Controller/stock/reporteStockController.php"><i class="fas fa-chart-bar"></i> Reportes</a>
            <a href="../../Controller/stock/transferirStock.php"><i class="fas fa-truck"></i> Transferir</a>
            <a href="../../Controller/stock/alertaStockController.php"><i class="fas fa-exclamation-triangle"></i> Alertas</a>

        </div>

        <!-- Tarjetas del Dashboard -->
        <div class="card-container">
            <div class="card">
                <div><i class="fas fa-boxes"></i> Inventario</div>
                <div><?php echo $totalProductos; ?></div>
                <div>Productos en total</div>
                <a href="../../Controller/productos/ListarProductosController.php">Ver listado <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="card">
                <div><i class="fas fa-exclamation-triangle"></i> Alertas</div>
                <div><?php echo count($productosBajoStock); ?></div>
                <div>Productos con stock bajo</div>
                <a href="../../Controller/stock/verInventarioController.php?filtro=bajo_stock">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>

            <div class="card">
                <div><i class="fas fa-truck"></i> Transferencias</div>
                <div><?php echo $transferenciaPendientes; ?></div>
                <div>Transferencias pendientes</div>
                <a href="../../Controller/stock/transferirStock.php">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="card">
            <div><i class="fas fa-clock"></i> Actividad Reciente</div>
            <div class="activity-content">
                <?php if (empty($movimientosRecientes)): ?>
                    <p>No hay actividad reciente registrada en el sistema.</p>
                    <div>
                        <div>
                            <i class="fas fa-lightbulb"></i>
                            <span>Consejo: Visite la sección de búsqueda avanzada para encontrar productos específicos.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <ul>
                        <?php foreach ($movimientosRecientes as $movimiento): 
                            // Definir iconos y colores una sola vez
                            $iconClass = '';
                            $tipoTexto = '';
                            
                            switch ($movimiento['tipo_movimiento']) {
                                case 'entrada':
                                    $iconClass = 'fas fa-arrow-circle-down';
                                    $tipoTexto = 'Entrada';
                                    break;
                                case 'salida':
                                    $iconClass = 'fas fa-arrow-circle-up';
                                    $tipoTexto = 'Salida';
                                    break;
                                case 'transferencia':
                                    $iconClass = 'fas fa-exchange-alt';
                                    $tipoTexto = 'Transferencia';
                                    break;
                                case 'ajuste':
                                    $iconClass = 'fas fa-sync';
                                    $tipoTexto = 'Ajuste';
                                    break;
                                default:
                                    $iconClass = 'fas fa-circle';
                                    $tipoTexto = ucfirst($movimiento['tipo_movimiento']);
                            }
                        ?>
                            <li>
                                <div>
                                    <div>
                                        <i class="<?php echo $iconClass; ?>"></i>
                                    </div>
                                    <div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($tipoTexto); ?></strong> - 
                                            <?php echo htmlspecialchars($movimiento['producto'] ?? 'Producto no especificado'); ?>
                                        </div>
                                        <div>
                                            Cantidad: <strong><?php echo htmlspecialchars($movimiento['cantidad'] ?? '0'); ?></strong>
                                            <?php if ($movimiento['tipo_movimiento'] == 'transferencia'): ?>
                                                <br>De: <?php echo htmlspecialchars($movimiento['almacen_origen'] ?? 'N/A'); ?> → 
                                                A: <?php echo htmlspecialchars($movimiento['almacen_destino'] ?? 'N/A'); ?>
                                            <?php elseif (!empty($movimiento['almacen_origen'])): ?>
                                                <br>Almacén: <?php echo htmlspecialchars($movimiento['almacen_origen']); ?>
                                            <?php endif; ?>
                                            <div>
                                                <small>
                                                    <?php echo date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])); ?> - 
                                                    Por: <?php echo htmlspecialchars($movimiento['usuario'] ?? 'Usuario desconocido'); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="../../Controller/stock/movimientoStockController.php?id=<?php echo $movimiento['id_movimiento'] ?? ''; ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div>
                        <a href="../../Controller/stock/movimientoStockController.php">
                            <i class="fas fa-list-ul"></i> Ver todos los movimientos
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Gráfico de Resumen -->
        <div class="card">
            <div><i class="fas fa-chart-pie"></i> Resumen de Inventario</div>
            <div>
                <div id="chart-container"></div>
            </div>
        </div>
        
        <!-- Botón para abrir el chat -->
        <div>
            <button id="open-chat-btn">
                <i class="fas fa-comment-dots"></i> Asistente de Inventario
            </button>
        </div>
        
        <!-- Ventana de chat -->
        <div id="chat-container">
            <div class="chat-header">
                <h5>Asistente de Inventario</h5>
                <button id="close-chat-btn" title="Cerrar chat">
                    <span class="close-x">×</span>
                </button>
            </div>
            <div id="chat-messages">
                <div>
                    Hola, soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?
                </div>
            </div>
            <div class="chat-input-container">
                <input type="text" id="chat-input-field" placeholder="Escribe tu consulta aquí...">
                <button id="send-chat-btn" title="Enviar mensaje">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Alertas -->
    <div id="alertModal" class="alert-modal">
        <div class="alert-content">
            <div style="background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white; padding: 20px 30px; border-radius: 15px 15px 0 0; display: flex; align-items: center; justify-content: space-between;">
                <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle"></i>
                    Alertas de Stock Bajo
                </h2>
                <button onclick="closeAlertModal()" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div style="padding: 30px;">
                <?php if (!empty($productosBajoStock)): ?>
                    <div style="background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 10px; padding: 20px; margin-bottom: 25px; border-left: 5px solid #ff9800;">
                        <h4><i class="fas fa-info-circle"></i> Resumen de Alertas</h4>
                        <p>Los siguientes productos requieren atención inmediata debido a su bajo nivel de stock.</p>
                        
                        <div style="display: flex; justify-content: space-around; margin: 20px 0;">
                            <div style="text-align: center; padding: 15px;">
                                <div style="font-size: 2rem; font-weight: bold; color: #ff5722;"><?php echo count($productosBajoStock); ?></div>
                                <div style="color: #666; font-size: 0.9rem; margin-top: 5px;">Productos con Stock Bajo</div>
                            </div>
                            <div style="text-align: center; padding: 15px;">
                                <div style="font-size: 2rem; font-weight: bold; color: #ff5722;">
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
                                <div style="color: #666; font-size: 0.9rem; margin-top: 5px;">Críticos</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($productosBajoStock as $producto): 
                            $isCritical = $producto['cantidad_disponible'] < $producto['stock_minimo'] * 0.5;
                            $percentage = ($producto['cantidad_disponible'] / $producto['stock_minimo']) * 100;
                        ?>
                            <div style="background: <?php echo $isCritical ? '#ffebee' : '#f8f9fa'; ?>; border-radius: 10px; padding: 20px; margin-bottom: 15px; border-left: 4px solid <?php echo $isCritical ? '#f44336' : '#ff9800'; ?>; display: flex; align-items: center; justify-content: space-between;">
                                <div style="flex: 1;">
                                    <div style="font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 5px;">
                                        <i class="<?php echo $isCritical ? 'fas fa-radiation' : 'fas fa-exclamation-triangle'; ?>"></i>
                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                    </div>
                                    
                                    <div style="display: flex; gap: 20px; margin-top: 10px;">
                                        <div style="text-align: center;">
                                            <div style="font-size: 1.3rem; font-weight: bold; color: <?php echo $isCritical ? '#f44336' : '#ff9800'; ?>;">
                                                <?php echo htmlspecialchars($producto['cantidad_disponible']); ?>
                                            </div>
                                            <div style="font-size: 0.8rem; color: #666; margin-top: 2px;">Disponible</div>
                                        </div>
                                        
                                        <div style="text-align: center;">
                                            <div style="font-size: 1.3rem; font-weight: bold;">
                                                <?php echo htmlspecialchars($producto['stock_minimo']); ?>
                                            </div>
                                            <div style="font-size: 0.8rem; color: #666; margin-top: 2px;">Mínimo</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="flex: 1; margin: 0 20px;">
                                    <div style="height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden; margin-bottom: 5px;">
                                        <div style="height: 100%; background: linear-gradient(90deg, <?php echo $isCritical ? '#f44336, #ef5350' : '#ff9800, #ffb74d'; ?>); width: <?php echo min($percentage, 100); ?>%;"></div>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #666; text-align: center;"><?php echo round($percentage, 1); ?>% del mínimo</div>
                                </div>
                                
                                <div style="display: flex; gap: 10px;">
                                    <a href="../../Controller/stock/ajustarStockController.php?id_producto=<?php echo $producto['id_producto']; ?>" 
                                       style="background: #2196f3; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-size: 0.9rem;">
                                        <i class="fas fa-edit"></i> Ajustar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px 20px;">
                        <div style="font-size: 4rem; color: #4caf50; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-size: 1.5rem; color: #333; margin-bottom: 10px;">¡No hay alertas de stock bajo!</div>
                        <div style="color: #666; margin-bottom: 20px;">
                            Todos los productos tienen niveles de stock adecuados. 
                            No hay productos que requieran atención en este momento.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="padding: 20px 30px; background: #f5f5f5; border-radius: 0 0 15px 15px; display: flex; justify-content: space-between; align-items: center;">
                <div style="background: #ff5722; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.9rem; font-weight: bold;">
                    <i class="fas fa-bell"></i>
                    <?php echo count($productosBajoStock); ?> alerta(s)
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <a href="../../Controller/stock/ajustarStockController.php" style="background: #2196f3; color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none;">
                        <i class="fas fa-edit"></i> Ajustar Stock
                    </a>
                    <button onclick="closeAlertModal()" style="background: #9e9e9e; color: white; padding: 10px 20px; border: none; border-radius: 25px; cursor: pointer;">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir el JavaScript del chat -->
    <script src="../../../public/js/chatbot.js"></script>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="../../../public/js/dark-mode.js"></script>
    <script>
        // Variables globales para JS
        var totalProductos = <?php echo $totalProductos; ?>;
        var stockBajo = <?php echo count($productosBajoStock); ?>;
        var transferencias = <?php echo $transferenciaPendientes; ?>;
        var productosBajoStock = <?php echo json_encode($productosBajoStock); ?>;
        var alertaStockMostrada = <?php echo $_SESSION['alerta_stock_mostrada'] ? 'true' : 'false'; ?>;
        var session_id = '<?php echo $_SESSION["session_id"] ?? ""; ?>';
    </script>
    <script src="../../../public/js/dashboard.js"></script>
    <script src="../../../public/js/session_keepalive.js"></script>
</body>
</html>




