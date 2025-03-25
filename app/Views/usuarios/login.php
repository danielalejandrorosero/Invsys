<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../../frontend/login.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form action="../../Controller/usuarios/sesionController.php" method="POST">
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>

        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red;"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
</body>
</html>
