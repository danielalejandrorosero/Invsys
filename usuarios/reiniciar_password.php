<?php

require_once '../config/cargarConfig.php';
require_once 'config_mail.php';


// horario de Colombia
date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $nuevaPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE token_recuperacion = ? AND expira_token > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // 🔹 Actualizar la contraseña y eliminar el token
        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
        $stmt->bind_param("si", $nuevaPassword, $usuario['id_usuario']);
        $stmt->execute();

        $stmt->close();

        if ($stmt->affected_rows > 0) {
            echo "Contraseña actualizada con éxito.";
            // y si se restablece que me mande a login
            header("Location: iniciarSesion.php");
            exit();
        } else {
            echo "Error al actualizar la contraseña.";
        }
    } else {
        echo "Token inválido o expirado.";
    }
} else {
    // si no se envía por POST, redireccionar a la página de solicitar recuperación
    header("Location: solicitar_recuperacion.php");
    exit();
}

?>
