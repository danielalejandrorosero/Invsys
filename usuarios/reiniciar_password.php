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

        // Actualizar la contraseña y eliminar el token
        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL, expira_token = NULL WHERE id_usuario = ?");
        $stmt->bind_param("si", $nuevaPassword, $usuario['id_usuario']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensaje = "Contraseña actualizada con éxito.";
            header("Location: iniciarSesion.php");
            exit();
        } else {
            $mensaje = "Error al actualizar la contraseña.";
        }
    } else {
        $mensaje = "Token inválido o expirado.";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    // Si no se proporciona un token, redirigimos a la página de solicitar recuperación
    header("Location: solicitar_recuperacion.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reiniciar Contraseña</title>
    <link rel="stylesheet" href="../frontend/reiniciarPassword.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Reiniciar Contraseña</h1>
        <form action="reiniciar_password.php" method="POST">
            <div class="form-group">
                <label for="token">Token de Recuperación</label>
                <input type="text" id="token" name="token" value="<?php echo isset($token) ? htmlspecialchars($token) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Reiniciar Contraseña</button>
        </form>
        <?php
        if (isset($mensaje)) {
            echo "<p>{$mensaje}</p>";
        }
        ?>
    </div>
</body>
</html>