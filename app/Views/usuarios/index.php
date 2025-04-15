<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Gestión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/index.css">
    <script>
        function showToast() {
            var toast = document.getElementById('toast-notification');
            toast.classList.add('show');
            // Ocultar automáticamente después de 8 segundos
            setTimeout(hideToast, 8000);
        }

        function hideToast() {
            var toast = document.getElementById('toast-notification');
            toast.classList.remove('show');
        }

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        window.onload = function() {
            showToast();
        }
    </script>
</head>
<body>
    <!-- Toggle Sidebar Button (visible on mobile) -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Stock Manager</h2>
            <div class="user-info">
                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h3><?php echo htmlspecialchars($nombreUsuario); ?></h3>
                    <p>Nivel: <?php echo htmlspecialchars(
                        $nivel_usuario
                    ); ?></p>
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

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <h1>Panel de Control</h1>
        </div>

        <!-- Dashboard Cards -->
        <div class="card-container">
            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-boxes"></i> Inventario</div>
                <div class="number">120</div>
                <div class="label">Productos en total</div>
            </div>

            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertas</div>
                <div class="number"><?php echo count(
                    $productosBajoStock
                ); ?></div>
                <div class="label">Productos con stock bajo</div>
            </div>

            <div class="card stat-card">
                <div class="card-title"><i class="fas fa-truck"></i> Transferencias</div>
                <div class="number">8</div>
                <div class="label">Transferencias pendientes</div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="card">
            <div class="card-title"><i class="fas fa-clock"></i> Actividad Reciente</div>
            <p>Bienvenido al sistema de gestión de stock. Utilice el menú de la izquierda para navegar por las diferentes funcionalidades.</p>
        </div>
    </div>

    <!-- Toast Notification for Low Stock -->
    <?php if (!empty($productosBajoStock)): ?>
        <div id="toast-notification">
            <div class="close-btn" onclick="hideToast()">×</div>
            <h3><i class="fas fa-exclamation-triangle"></i> Alertas de Stock Bajo</h3>
            <ul>
                <?php foreach ($productosBajoStock as $producto): ?>
                    <li>
                        <i class="fas fa-box"></i> <?php echo htmlspecialchars(
                            $producto["nombre"]
                        ); ?> -
                        <strong>Stock: <?php echo htmlspecialchars(
                            $producto["cantidad_disponible"]
                        ); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

</body>
</html>
