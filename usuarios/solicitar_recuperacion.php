<?php

require_once '../config/cargarConfig.php';
require_once 'config_mail.php';

date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // 🔹 Limpiar el token anterior
        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
        $stmt->bind_param("i", $usuario['id_usuario']);
        $stmt->execute();

        // 🔹 Generar nuevo token y expiración
        $token = bin2hex(random_bytes(50));
        $expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, expira_token = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $token, $expira, $usuario['id_usuario']);
        $stmt->execute();
        
        if (enviarCorreoRecuperacion($correo, $token)) {
            echo "Correo de recuperación enviado.";
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        echo "Correo no registrado.";
    }
} else {
    echo "Método no permitido.";
}

?>
