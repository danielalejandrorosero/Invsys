<?php

require_once '../config/cargarConfig.php';
require_once 'config_mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Limpiar el token anterior
        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
        $stmt->bind_param("i", $usuario['id_usuario']);
        $stmt->execute();

        // Generar nuevo token y expiración
        $token = bin2hex(random_bytes(50));
        $expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $conn->prepare("UPDATE usuarios SET token_recuperacion = ?, expira_token = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $token, $expira, $usuario['id_usuario']);
        $stmt->execute();
        
        if (enviarCorreoRecuperacion($correo, $token)) {
            $mensaje = "Correo de recuperación enviado.";
        } else {
            $mensaje = "Error al enviar el correo.";
        }
    } else {
        $mensaje = "Correo no registrado.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../frontend/solicitarRecuperacion.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Recuperar Contraseña</h1>
        <form action="solicitar_recuperacion.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <button type="submit">Enviar Correo de Recuperación</button>
        </form>
        <?php
        if (isset($mensaje)) {
            echo "<p>{$mensaje}</p>";
        }
        ?>
    </div>
</body>
</html>