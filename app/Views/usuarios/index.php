<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Controller/stock/alertaStockController.php';
session_start();
    
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// ACOMODAR REDIRECCIONES TODOS LOS ARCHIVOS


// Obtener información del usuario  
$id_usuario = $_SESSION['id_usuario'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$nivel_usuario = $_SESSION['nivel_usuario'];

// inicializar el controlador de stock y obtener 
$stockController = new AlertaStockController($conn);
$productosBajoStock = $stockController->alertaStock();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../../../frontend/index.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
    <style>
        /* Estilos para la notificación en la parte superior */
        #toast-notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.3);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        #toast-notification.show {
            opacity: 1;
        }
        #toast-notification h3 {
            margin: 0 0 10px;
            font-size: 20px;
            text-align: center;
        }
        #toast-notification ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #toast-notification ul li {
            margin-bottom: 5px;
            font-size: 16px;
        }
        #toast-notification .close-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
    <script>
        function showToast() {
            var toast = document.getElementById('toast-notification');
            toast.classList.add('show');
            // Ocultar automáticamente después de 5 segundos
            setTimeout(hideToast, 5000);
        }
        function hideToast() {
            var toast = document.getElementById('toast-notification');
            toast.classList.remove('show');
        }
        window.onload = function() {
            showToast();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        <nav>
            <ul>
                <li><a href="../../Controller/usuarios/agregarController.php">Agregar Usuario</a></li>
                <li><a href="../../Controller/usuarios/listarUsuarios.php">Lista de Usuarios</a></li>
                <li><a href="../../Controller/usuarios/cerrarSesionController.php">Cerrar Sesión</a></li>
                <li><a href="../../Controller/usuarios/solicitarRecuperacionController.php">Recuperar Contraseña</a></li>
                <li><a href="../../Controller/usuarios/eliminarUsuarioController.php">Eliminar Usuario</a></li>
                <li><a href="../../Controller/usuarios/editarUsuario.php">Editar Usuario</a></li>
                <li><a href="../../Controller/stock/verInventarioController.php">Ver Inventario</a></li>
                <li><a href="../../Controller/stock/ajustarStockController.php">Ajustar Stock</a></li>
                <li><a href="../../Controller/stock/movimientoStockController.php">Movimientos de Stock</a></li>
                <li><a href="../../Controller/stock/reporteStockController.php">Reporte de Stock</a></li>
                <li><a href="../../Controller/stock/transferirStock.php">Transferir Stock</a></li>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=producto">Colocar Imagen Producto</a></li>
                <li><a href="../../Controller/subirImagenes/SubirImagenController.php?tipo=usuario">Colocar Imagen Usuario</a></li>
            </ul>
        </nav>
    </div>

    <?php if (!empty($productosBajoStock)) : ?>
        <div id="toast-notification">
            <span class="close-btn" onclick="hideToast()">×</span>
            <h3>Alertas de Stock Bajo</h3>
            <ul>
                <?php foreach ($productosBajoStock as $producto) : ?>
                    <li>
                        ⚠️ <?php echo htmlspecialchars($producto['nombre']); ?> - Cantidad: <?php echo htmlspecialchars($producto['cantidad_disponible']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

</body>
</html>