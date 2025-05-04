<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";
require_once __DIR__ . "/../../Models/productos/productos.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";

// OBTENERR LA IMAGEN DEL USUARIO
$usuario = new Usuario($conn);

$stock = new Stock($conn);
$productos = new Productos($conn);

$productosBajoStock = $stock->obtenerProductosBajoStock();
$totalProductos = $productos->contarTotalProductos();
$transferenciaPendientes = $stock->contarTransferenciasPendientes();

// Obtener los últimos movimientos para mostrar en actividad reciente
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

$nombreUsuario = $_SESSION["nombreUsuario"] ?? "Nombre del Usuario";
$nivel_usuario = $_SESSION["nivel_usuario"] ?? "Nivel del Usuario";

// Ruta de la imagen del usuario - Simplificada como en listarProductosView.php
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Favicon para mejorar la identidad visual -->
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
    <!-- Meta tags para SEO y accesibilidad -->
    <meta name="description" content="Panel de control del sistema de gestión de inventario InvSys">
    <meta name="theme-color" content="#2c3e50">
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
                        <!-- Depues de colocar  la funcion para actualziar la imagen y no perder la imagen tras cerrar sesion-->
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

        <!-- Accesos Rápidos -->
        <div class="quick-actions">
            <a href="../../Controller/productos/agregarProductoController.php" class="quick-action-btn"><i class="fas fa-plus-circle"></i> Nuevo Producto</a>
            <a href="../../Controller/stock/ajustarStockController.php" class="quick-action-btn"><i class="fas fa-edit"></i> Ajustar Stock</a>
            <a href="../../Controller/stock/reporteStockController.php" class="quick-action-btn"><i class="fas fa-chart-bar"></i> Reportes</a>
            <a href="../../Controller/stock/transferirStock.php" class="quick-action-btn"><i class="fas fa-truck"></i> Transferir</a>
        </div>

        <!-- Tarjetas del Dashboard -->
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
                <a href="alertStock.php" class="btn red waves-effect waves-light">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>

            <div class="card stat-card hoverable <?php echo $transferenciaPendientes > 0 ? 'pulse-info' : ''; ?>">
                <div class="card-title"><i class="fas fa-truck"></i> Transferencias</div>
                <div class="number <?php echo $transferenciaPendientes > 0 ? 'text-info' : ''; ?>"><?php echo $transferenciaPendientes; ?></div>
                <div class="label">Transferencias pendientes</div>
                <a href="../../Controller/stock/transferirStock.php" class="btn blue waves-effect waves-light">Ver Detalles <i class="fas fa-eye"></i></a>
            </div>
        </div>

        <!-- Actividad Reciente -->
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
                        <?php foreach ($movimientosRecientes as $movimiento): ?>
                            <li class="collection-item avatar">
                                <?php 
                                $iconClass = '';
                                $tipoTexto = '';
                                switch ($movimiento['tipo_movimiento']) {
                                    case 'entrada':
                                        $iconClass = 'fas fa-arrow-circle-down green-text';
                                        $tipoTexto = 'Entrada';
                                        break;
                                    case 'salida':
                                        $iconClass = 'fas fa-arrow-circle-up red-text';
                                        $tipoTexto = 'Salida';
                                        break;
                                    case 'transferencia':
                                        $iconClass = 'fas fa-exchange-alt blue-text';
                                        $tipoTexto = 'Transferencia';
                                        break;
                                    case 'ajuste':
                                        $iconClass = 'fas fa-sync orange-text';
                                        $tipoTexto = 'Ajuste';
                                        break;
                                    default:
                                        $iconClass = 'fas fa-circle grey-text';
                                        $tipoTexto = ucfirst($movimiento['tipo_movimiento']);
                                }
                                ?>
                                <i class="<?php echo $iconClass; ?> circle"></i>
                                <span class="title"><strong><?php echo htmlspecialchars($tipoTexto); ?></strong> - <?php echo htmlspecialchars($movimiento['producto']); ?></span>
                                <p>
                                    Cantidad: <strong><?php echo htmlspecialchars($movimiento['cantidad']); ?></strong><br>
                                    <?php if ($movimiento['tipo_movimiento'] == 'transferencia'): ?>
                                        De: <?php echo htmlspecialchars($movimiento['almacen_origen'] ?? 'N/A'); ?> → 
                                        A: <?php echo htmlspecialchars($movimiento['almacen_destino'] ?? 'N/A'); ?><br>
                                    <?php elseif (!empty($movimiento['almacen_origen'])): ?>
                                        Almacén: <?php echo htmlspecialchars($movimiento['almacen_origen']); ?><br>
                                    <?php endif; ?>
                                    <small class="grey-text"><?php echo date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])); ?> - Por: <?php echo htmlspecialchars($movimiento['usuario']); ?></small>
                                </p>
                                <a href="../../Controller/stock/movimientoStockController.php" class="secondary-content"><i class="fas fa-eye"></i></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="center-align">
                        <a href="../../Controller/stock/movimientoStockController.php" class="btn-flat waves-effect">Ver todos los movimientos <i class="fas fa-arrow-right"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Gráfico de Resumen -->
        <div class="card hoverable">
            <div class="card-title"><i class="fas fa-chart-pie"></i> Resumen de Inventario</div>
            <div class="card-content">
                <div id="chart-container" style="height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar componentes de Materialize
            M.AutoInit();
            
            // Inicializar componentes de Materialize
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
                    }, index * 300); // Mostrar notificaciones con un pequeño retraso entre ellas
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
                        height: 250
                    },
                    colors: ['#4caf50', '#ff9800', '#2196f3'],
                    legend: {
                        position: 'bottom'
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
                    }
                };
                
                const chart = new ApexCharts(document.getElementById('chart-container'), options);
                chart.render();
            }
        });
        
        // Función para verificar el ancho de la pantalla
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar').classList.remove('show');
            } else {
                document.querySelector('.sidebar').classList.add('show');
            }
        }
        
        // La función para búsqueda rápida ahora está integrada en la inicialización del DOM
    </script>
</body>
</html>