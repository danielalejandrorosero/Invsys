<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | InvSys</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-boxes"></i>
                </div>
                <h1>InvSys</h1>
                <p>Ingrese sus credenciales para acceder</p>
            </div>

            <div class="login-body">
                <form action="../app/Controller/usuarios/sesionController.php" method="POST">
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese su nombre de usuario" required>
                    </div>

                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Recordarme</label>
                        </div>
                        <a href="../app/Controller/usuarios/solicitarRecuperacionController.php" class="forgot-password">¿Olvidó su contraseña?</a>
                    </div>

                    <button type="submit" name="login" class="login-button">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <?php if (!empty($_SESSION["error"])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p><?php echo $_SESSION["error"]; ?></p>
                    </div>
                    <?php unset($_SESSION["error"]); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="login-footer">
            &copy; <?php echo date("Y"); ?> InvSys. Todos los derechos reservados.
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>