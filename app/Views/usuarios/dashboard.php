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
    <link rel="stylesheet" href="../../../public/css/index.css">

    <link rel="stylesheet" href="../../../public/css/chatbot.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
    <meta name="description" content="Panel de control del sistema de gestión de inventario InvSys">
    <meta name="theme-color" content="#2c3e50">
    

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
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Control</h1>
            <p>Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>. Última actualización: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <!-- Accesos Rápidos -->
        <div class="quick-actions">
            <a href="../../Controller/productos/agregarProductoController.php"><i class="fas fa-plus-circle"></i> Nuevo Producto</a>
            <a href="../../Controller/stock/ajustarStockController.php"><i class="fas fa-edit"></i> Ajustar Stock</a>
            <a href="../../Controller/stock/reporteStockController.php"><i class="fas fa-chart-bar"></i> Reportes</a>
            <a href="../../Controller/stock/transferirStock.php"><i class="fas fa-truck"></i> Transferir</a>
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

    <!-- Incluir el JavaScript del chat -->
    <script src="../../../public/js/chatbot.js"></script>

    <!-- Scripts -->
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
            // Inicializar componentes de Materialize
            M.AutoInit();
            
            // Mostrar notificaciones de stock bajo
            <?php if (!empty($productosBajoStock)): ?>
                var productos = <?php echo json_encode($productosBajoStock); ?>;
                productos.forEach(function (producto, index) {
                    setTimeout(function() {
                        M.toast({
                            html: '<i class="fas fa-exclamation-circle"></i> <b>Stock bajo:</b> ' + producto.nombre + ' - Cantidad: ' + producto.cantidad_disponible,
                            displayLength: 8000
                        });
                    }, index * 300 + 1000);
                });
            <?php endif; ?>
            
            // Detectar tamaño de pantalla al cargar
            checkScreenSize();
            
            // Detectar cambios en el tamaño de la pantalla
            window.addEventListener('resize', checkScreenSize);
            
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
            
            // Verificar si la imagen se actualizó
            if (window.location.search.includes('img_updated')) {
                M.toast({
                    html: '<i class="fas fa-check-circle"></i> ¡Imagen de perfil actualizada correctamente!',
                    displayLength: 3000
                });
                
                // Limpiar la URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>




