<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

$stock = new Stock($conn);
$productos = new Productos($conn);

$productosBajoStock = $stock->obtenerProductosBajoStock();

$nombreUsuario = $_SESSION["nombreUsuario"] ?? "Nombre del Usuario";
$nivel_usuario = $_SESSION["nivel_usuario"] ?? "Nivel del Usuario";

// Ruta de la imagen del usuario
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
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
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

            <h3>Sistema</h3>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../../Controller/usuarios/cerrarSesionController.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-exclamation-triangle"></i> Alertas de Stock Bajo</h1>
            <p class="welcome-message">Bienvenido, <strong><?php echo htmlspecialchars($nombreUsuario); ?></strong>. Última actualización: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <!-- Acciones Rápidas -->
        <div class="quick-actions">
            <a href="../../Controller/stock/ajustarStockController.php" class="quick-action-btn"><i class="fas fa-edit"></i> Ajustar Stock</a>
            <a href="../../Controller/stock/transferirStock.php" class="quick-action-btn"><i class="fas fa-truck"></i> Transferir Stock</a>
            <a href="dashboard.php" class="quick-action-btn"><i class="fas fa-tachometer-alt"></i> Volver al Dashboard</a>
        </div>

        <!-- Tabla de Productos con Stock Bajo -->
        <div class="card hoverable">
            <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Productos con Stock Bajo</div>
            <div class="card-content">
                <?php if (empty($productosBajoStock)): ?>
                    <p class="center-align">No hay productos con stock bajo en este momento.</p>
                <?php else: ?>
                    <table class="striped highlight responsive-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Almacén</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productosBajoStock as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['id_producto']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['almacen']); ?></td>
                                    <td class="<?php echo $producto['cantidad_disponible'] == 0 ? 'red-text' : 'orange-text'; ?>">
                                        <strong><?php echo htmlspecialchars($producto['cantidad_disponible']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($producto['stock_minimo']); ?></td>
                                    <td>
                                        <?php if ($producto['cantidad_disponible'] == 0): ?>
                                            <span class="new badge red" data-badge-caption="Sin Stock"></span>
                                        <?php else: ?>
                                            <span class="new badge orange" data-badge-caption="Stock Bajo"></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../../Controller/stock/ajustarStockController.php?id=<?php echo $producto['id_producto']; ?>" class="btn-small waves-effect waves-light blue">
                                            <i class="fas fa-edit"></i> Ajustar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recomendaciones -->
        <div class="card hoverable">
            <div class="card-title"><i class="fas fa-lightbulb"></i> Recomendaciones</div>
            <div class="card-content">
                <ul class="collection">
                    <li class="collection-item"><i class="fas fa-check-circle green-text"></i> Revise regularmente los productos con stock bajo</li>
                    <li class="collection-item"><i class="fas fa-check-circle green-text"></i> Ajuste los niveles de stock mínimo según la demanda</li>
                    <li class="collection-item"><i class="fas fa-check-circle green-text"></i> Considere transferir stock desde otros almacenes</li>
                    <li class="collection-item"><i class="fas fa-check-circle green-text"></i> Planifique los pedidos con anticipación</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar componentes de Materialize
            M.AutoInit();
            
            // Detectar tamaño de pantalla al cargar
            checkScreenSize();
            
            // Detectar cambios en el tamaño de la pantalla
            window.addEventListener('resize', checkScreenSize);
        });
        
        // Función para verificar el ancho de la pantalla
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar').classList.remove('show');
            } else {
                document.querySelector('.sidebar').classList.add('show');
            }
        }
    </script>
</body>
</html>