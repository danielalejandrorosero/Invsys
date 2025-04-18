<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/stock/stock.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

$stock = new Stock($conn);
$productos = new Productos($conn);

$productosBajoStock = $stock->obtenerProductosBajoStock();
$totalProductos = $productos->contarTotalProductos();

$nombreUsuario = $_SESSION["nombreUsuario"] ?? "Nombre del Usuario";
$nivel_usuario = $_SESSION["nivel_usuario"] ?? "Nivel del Usuario";

// Ruta de la imagen del usuario
$rutaImagen =
    !empty($_SESSION["rutaImagen"]) &&
    file_exists(__DIR__ . "/../../../../" . $_SESSION["rutaImagen"])
        ? $_SESSION["rutaImagen"]
        : "../../../public/uploads/imagenes/usuarios/default-avatar.png";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Gestión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Botón para móviles -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Stock Manager</h2>
            <div class="user-info">
                <div class="avatar">
                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="Avatar" class="circle responsive-img">
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
            <h1>Panel de Control</h1>
        </div>

        <!-- Tarjetas del Dashboard -->
        <div class="card-container">
            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-boxes"></i> Inventario</div>
                <div class="number"><?php echo $totalProductos; ?></div>
                <div class="label">Productos en total</div>
            </div>

            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertas</div>
                <div class="number"><?php echo count($productosBajoStock); ?></div>
                <div class="label">Productos con stock bajo</div>
                <a href="alertStock.php" class="btn red">Ver Detalles</a>
            </div>

            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-truck"></i> Transferencias</div>
                <div class="number">8</div>
                <div class="label">Transferencias pendientes</div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="card">
            <div class="card-title"><i class="fas fa-clock"></i> Actividad Reciente</div>
            <p>Bienvenido al sistema de gestión de stock. Utilice el menú de la izquierda para navegar por las diferentes funcionalidades.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function () {
            <?php if (!empty($productosBajoStock)): ?>
                var productos = <?php echo json_encode($productosBajoStock); ?>;
                productos.forEach(function (producto) {
                    M.toast({
                        html: 'Stock bajo: ' + producto.nombre + ' - Cantidad: ' + producto.cantidad_disponible,
                        displayLength: 8000
                    });
                });
            <?php endif; ?>
            
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