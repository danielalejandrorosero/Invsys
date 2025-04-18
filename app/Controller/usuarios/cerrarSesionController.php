<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar variables de sesión y destruir la sesión
session_unset();
session_destroy();

// Redirigir al login correctamente
header("Location: ../../../public/index.php");
exit();
?>