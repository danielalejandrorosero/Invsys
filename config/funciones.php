<?php

function validarCampos($camposRequeridos) {
    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            die("Error: El campo '{$campo}' es obligatorio.");
        }
    }
}

function nivelRequerido($nivel) {
    global $sesion;

    if (!isset($sesion)) {
        throw new Exception("Error: La clase Sesiones no está definida.");
    }

    if ($sesion->usuarioAutenticado() === false) {
        throw new Exception("Error: El usuario no está autenticado.");
    }
    
    $usuarioActual = UsuarioActual();
    if (!$usuarioActual) {
        throw new Exception("Error: No se pudo obtener información del usuario actual.");
    }

    $nivel_sesion = encontrarPorGrupoNivel($usuarioActual['nivel_usuario']);
    if (!$nivel_sesion) {
        throw new Exception("Error: No se encontró el nivel del usuario en la base de datos.");
    }

    if ($nivel_sesion['estado_grupo'] === 0) {
        throw new Exception("Error: El grupo de usuario está inactivo.");
    }

    if ($nivel_sesion['nivel_grupo'] < (int) $nivel) {
        throw new Exception("Error: No tienes permisos para acceder a esta página.");
    }
}

function encontrarPorGrupoNivel($nivel) {
    global $conn;

    $sql = "SELECT nivel_grupo, estado_grupo FROM grupos WHERE nivel_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nivel);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $grupo = ($resultado->num_rows > 0) ? $resultado->fetch_assoc() : null;

    $stmt->close();
    return $grupo;
}

function UsuarioActual() {
    static $usuarioActual = null;
    global $conn;

    if ($usuarioActual === null && isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];

        $stmt = $conn->prepare("SELECT id_usuario, nombre, email, password, status, last_login, nivel_usuario, nombreUsuario FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuarioActual = $resultado->fetch_assoc(); 
        }

        $stmt->close();
    }

    if (!isset($usuarioActual['nivel_usuario'])) {
        throw new Exception("Error: No se encontró el nivel de usuario en la sesión.");
    }

    return $usuarioActual;
}

?>
