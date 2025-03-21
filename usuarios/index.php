<?php

require_once '../config/cargarConfig.php';

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciarSesion.php");
    exit();
}

// Obtener información del usuario
$id_usuario = $_SESSION['id_usuario'];
$nombreUsuario = $_SESSION['nombreUsuario'];
$nivel_usuario = $_SESSION['nivel_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="../frontend/index.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        <nav>
            <ul>
                <li><a href="agregarUsuario.php">Agregar Usuario</a></li>
                <li><a href="listaUsuarios.php">Lista de Usuarios</a></li>
                <li><a href="cerrarSesion.php">Cerrar Sesión</a></li>
                <li><a href="solicitar_recuperacion.php">Recuperar Contraseña</a></li>
                <li><a href="eliminarUsuario.php">Eliminar Usuario</a></li>
                <li><a href="editarUsuario.php">Editar Usuario</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>