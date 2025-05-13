<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";
require_once __DIR__ . "/../../Models/productos/productos.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";

// Verificar si el usuario está autenticado (una sola vez)
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../../public/index.php");
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/dark-mode.css">
    <link rel="stylesheet" href="../../../public/css/chatbot.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
    <meta name="description" content="Panel de control del sistema de gestión de inventario InvSys">
    <meta name="theme-color" content="#2c3e50">
    
    <style>
        /* Estilos flat y minimalistas para el dashboard */
        /* Estilos para las tarjetas de estadísticas */
        .pulse-warning {
            border-left: 4px solid #ff9800;
        }
        
        .pulse-info {
            border-left: 4px solid #2196f3;
        }
        
        /* Estilos para el chat */
        .chat-button-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        #open-chat-btn {
            border-radius: 4px;
            background-color: #3498db;
            color: white;
            border: none;
            box-shadow: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            font-weight: 400;
            text-transform: none;
        }
        
        .chat-container {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 320px;
            height: 400px;
            background-color: white;
            border-radius: 8px;
            border: 1px solid #eaeaea;
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }
        
        .chat-header {
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chat-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }
        
        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }
        
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            max-width: 80%;
        }
        
        .bot-message {
            background-color: #f0f0f0;
            align-self: flex-start;
        }
        
        .user-message {
            background-color: #e3f2fd;
            align-self: flex-end;
            margin-left: auto;
        }
        
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #eaeaea;
        }
        
        #chat-input-field {
            flex: 1;
            border: 1px solid #eaeaea;
            border-radius: 4px;
            padding: 8px 12px;
            margin-right: 10px;
        }
        
        #send-chat-btn {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <!-- Botón para móviles -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>InvSys</h2>
            <div class="user-info">
                <div class="avatar">
                    <img src="../../../public/uploads/imagenes/usuarios/<?php echo htmlspecialchars($nombreArchivo); ?>?v=<?php echo time(); ?>" alt="Avatar" class="circle responsive-img">
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
            </ul>

            <h3>Productos</h3>
            <ul>
                <li><a href="../../Controller/productos/agregarProductoController.php"><i class="fas fa-plus-circle"></i> Agregar Producto</a></li>
                <li><a href="../../Controller/productos/buscarProductosController.php"><i class="fas fa-search"></i> Buscar Producto</a></li>
                <li><a href="../../Controller/productos/editarProductoController.php"><i class="fas fa-edit"></i> Editar Producto</a></li>
                <li><a href="../../Controller/productos/eliminarProductoController.php"><i class="fas fa-trash-alt"></i> Eliminar Producto</a></li>
                <li><a href="../../Controller/productos/RestaurarProductoController.php"><i class="fas fa-trash-restore"></i> Restaurar Producto</a></li>
                <li><a href="../../Controller/productos/ListarProductosController.php"><i class="fas fa-list"></i> Listar Productos</a></li>
            </ul>

            <h3>Imágenes</h3>
            <ul>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=producto"><i class="fas fa-image"></i> Imágenes de Productos</a></li>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=usuario"><i class="fas fa-user-circle"></i> Imágenes de Usuarios</a></li>
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
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Control</h1>
            <p class="welcome-message">Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>. Última actualización: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <!-- Accesos Rápidos (optimizados) -->
        <div class="quick-actions">
            <a href="../../Controller/productos/agregarProductoController.php" class="quick-action-btn"><i class="fas fa-plus-circle"></i> Nuevo Producto</a>
            <a href="../../Controller/stock/ajustarStockController.php" class="quick-action-btn"><i class="fas fa-edit"></i> Ajustar Stock</a>
            <a href="../../Controller/stock/reporteStockController.php" class="quick-action-btn"><i class="fas fa-chart-bar"></i> Reportes</a>
            <a href="../../Controller/stock/transferirStock.php" class="quick-action-btn"><i class="fas fa-truck"></i> Transferir</a>
        </div>

        <!-- Tarjetas del Dashboard (optimizadas) -->
        <div class="card-container">
            <div class="card stat-card hoverable pulse">
                <div class="card-title"><i class="fas fa-boxes"></i> Inventario</div>
                <div class="number"><?php echo $totalProductos; ?></div>
                <div class="label">Productos en total</div>
                <a href="../../Controller/productos/ListarProductosController.php" class="btn-flat waves-effect">Ver listado <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="card stat-card hoverable <?php echo count($productosBajoStock) > 0 ? 'pulse-warning' : ''; ?>">
                <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertas</div>
                <div class="number <?php echo count($productosBajoStock) > 0 ? 'text-warning' : ''; ?>"><?php echo count($productosBajoStock); ?></div>
                <div class="label">Productos con stock bajo</div>
                <a href="../../Controller/stock/verInventarioController.php?filtro=bajo_stock" class="btn red waves-effect waves-light">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>

            <div class="card stat-card hoverable <?php echo $transferenciaPendientes > 0 ? 'pulse-info' : ''; ?>">
                <div class="card-title"><i class="fas fa-truck"></i> Transferencias</div>
                <div class="number <?php echo $transferenciaPendientes > 0 ? 'text-info' : ''; ?>"><?php echo $transferenciaPendientes; ?></div>
                <div class="label">Transferencias pendientes</div>
                <a href="../../Controller/stock/transferirStock.php" class="btn blue waves-effect waves-light">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>
        </div>

        <!-- Actividad Reciente (optimizada) -->
        <div class="card hoverable">
            <div class="card-title"><i class="fas fa-clock"></i> Actividad Reciente</div>
            <div class="activity-content">
                <?php if (empty($movimientosRecientes)): ?>
                    <p>No hay actividad reciente registrada en el sistema.</p>
                    <div class="tips-container">
                        <div class="tip">
                            <i class="fas fa-lightbulb tip-icon"></i>
                            <span>Consejo: Visite la sección de búsqueda avanzada para encontrar productos específicos.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <ul class="collection">
                        <?php foreach ($movimientosRecientes as $movimiento): 
                            // Definir iconos y colores una sola vez
                            $iconClass = '';
                            $iconColor = '';
                            $tipoTexto = '';
                            
                            switch ($movimiento['tipo_movimiento']) {
                                case 'entrada':
                                    $iconClass = 'fas fa-arrow-circle-down';
                                    $iconColor = 'green-text';
                                    $tipoTexto = 'Entrada';
                                    break;
                                case 'salida':
                                    $iconClass = 'fas fa-arrow-circle-up';
                                    $iconColor = 'red-text';
                                    $tipoTexto = 'Salida';
                                    break;
                                case 'transferencia':
                                    $iconClass = 'fas fa-exchange-alt';
                                    $iconColor = 'blue-text';
                                    $tipoTexto = 'Transferencia';
                                    break;
                                case 'ajuste':
                                    $iconClass = 'fas fa-sync';
                                    $iconColor = 'orange-text';
                                    $tipoTexto = 'Ajuste';
                                    break;
                                default:
                                    $iconClass = 'fas fa-circle';
                                    $iconColor = 'grey-text';
                                    $tipoTexto = ucfirst($movimiento['tipo_movimiento']);
                            }
                        ?>
                            <li class="collection-item">
                                <div class="row" style="margin-bottom: 0;">
                                    <div class="col s1 m1">
                                        <i class="<?php echo $iconClass . ' ' . $iconColor; ?> movement-icon"></i>
                                    </div>
                                    <div class="col s9 m10">
                                        <div class="movement-title">
                                            <strong><?php echo htmlspecialchars($tipoTexto); ?></strong> - 
                                            <?php echo htmlspecialchars($movimiento['producto'] ?? 'Producto no especificado'); ?>
                                        </div>
                                        <div class="movement-details">
                                            Cantidad: <strong><?php echo htmlspecialchars($movimiento['cantidad'] ?? '0'); ?></strong>
                                            <?php if ($movimiento['tipo_movimiento'] == 'transferencia'): ?>
                                                <br>De: <?php echo htmlspecialchars($movimiento['almacen_origen'] ?? 'N/A'); ?> → 
                                                A: <?php echo htmlspecialchars($movimiento['almacen_destino'] ?? 'N/A'); ?>
                                            <?php elseif (!empty($movimiento['almacen_origen'])): ?>
                                                <br>Almacén: <?php echo htmlspecialchars($movimiento['almacen_origen']); ?>
                                            <?php endif; ?>
                                            <div class="movement-date grey-text">
                                                <small>
                                                    <?php echo date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])); ?> - 
                                                    Por: <?php echo htmlspecialchars($movimiento['usuario'] ?? 'Usuario desconocido'); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s2 m1 right-align">
                                        <a href="../../Controller/stock/movimientoStockController.php?id=<?php echo $movimiento['id_movimiento'] ?? ''; ?>" 
                                           class="btn-floating btn-small waves-effect waves-light blue">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="center-align" style="margin-top: 15px;">
                        <a href="../../Controller/stock/movimientoStockController.php" class="btn waves-effect waves-light">
                            <i class="fas fa-list-ul left"></i> Ver todos los movimientos
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Gráfico de Resumen (optimizado) -->
        <div class="card hoverable">
            <div class="card-title"><i class="fas fa-chart-pie"></i> Resumen de Inventario</div>
            <div class="card-content">
                <div id="chart-container" style="height: 250px;"></div>
            </div>
        </div>
        
        <!-- Botón para abrir el chat -->
        <div class="chat-button-container">
            <button id="open-chat-btn" class="btn waves-effect waves-light">
                <i class="fas fa-comment-dots left"></i> Asistente de Inventario
            </button>
        </div>
        
        <!-- Ventana de chat (inicialmente oculta) -->
        <div id="chat-container" class="chat-container">
            <div class="chat-header">
                <h5>Asistente de Inventario</h5>
                <button id="close-chat-btn" class="btn-flat">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-messages" id="chat-messages">
                <div class="message bot-message">
                    Hola, soy tu asistente de inventario. ¿En qué puedo ayudarte hoy?
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="chat-input-field" placeholder="Escribe tu consulta aquí...">
                <button id="send-chat-btn" class="btn waves-effect waves-light">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Incluir el JavaScript del chat -->
    <script src="../../../public/js/chatbot.js"></script>

    <!-- Scripts (optimizados) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="../../../public/js/dark-mode.js"></script>
    <script>
        // Función para alternar la barra lateral
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Función para verificar el ancho de la pantalla
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar').classList.remove('show');
            } else {
                document.querySelector('.sidebar').classList.add('show');
            }
        }

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar componentes de Materialize (una sola vez)
            M.AutoInit();
            
            // Mostrar notificaciones de stock bajo
            <?php if (!empty($productosBajoStock)): ?>
                var productos = <?php echo json_encode($productosBajoStock); ?>;
                productos.forEach(function (producto, index) {
                    setTimeout(function() {
                        M.toast({
                            html: '<i class="fas fa-exclamation-circle"></i> <b>Stock bajo:</b> ' + producto.nombre + ' - Cantidad: ' + producto.cantidad_disponible,
                            displayLength: 8000,
                            classes: 'red darken-2 rounded'
                        });
                    }, index * 300 + 1000);
                });
            <?php endif; ?>
            
            // Detectar tamaño de pantalla al cargar
            checkScreenSize();
            
            // Detectar cambios en el tamaño de la pantalla
            window.addEventListener('resize', checkScreenSize);
            
            // Añadir efecto de hover a las tarjetas
            document.querySelectorAll('.stat-card').forEach(function(card) {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('z-depth-3');
                });
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('z-depth-3');
                });
            });
            
            // Inicializar gráfico de resumen
            const totalProductos = <?php echo $totalProductos; ?>;
            const stockBajo = <?php echo count($productosBajoStock); ?>;
            const transferencias = <?php echo $transferenciaPendientes; ?>;
            
            if (document.getElementById('chart-container')) {
                const options = {
                    series: [totalProductos - stockBajo, stockBajo, transferencias],
                    labels: ['Productos con stock normal', 'Productos con stock bajo', 'Transferencias pendientes'],
                    chart: {
                        type: 'donut',
                        height: 250,
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 350
                            }
                        }
                    },
                    colors: ['#4caf50', '#ff9800', '#2196f3'],
                    legend: {
                        position: 'bottom',
                        formatter: function(seriesName, opts) {
                            return [seriesName, ': ', opts.w.globals.series[opts.seriesIndex]].join('')
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                height: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " unidades";
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val, opts) {
                            return opts.w.globals.series[opts.seriesIndex]
                        }
                    }
                };
                
                const chart = new ApexCharts(document.getElementById('chart-container'), options);
                chart.render();
            }
            
            // Verificar si la imagen se actualizó (parámetro en la URL)
            if (window.location.search.includes('img_updated')) {
                M.toast({
                    html: '<i class="fas fa-check-circle"></i> ¡Imagen de perfil actualizada correctamente!',
                    displayLength: 3000,
                    classes: 'green darken-2 rounded'
                });
                
                // Limpiar la URL (eliminar el parámetro)
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>