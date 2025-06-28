<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar el session_id de la base de datos si el usuario está autenticado
if (isset($_SESSION["id_usuario"])) {
    $usuarioModel = new Usuario($conn);
    $usuarioModel->limpiarSesion($_SESSION["id_usuario"]);
}

// Limpiar variables de sesión y destruir la sesión
session_unset();
session_destroy();

// Redirigir al login correctamente
header("Location: ../../../public/index.php");
exit();
?>