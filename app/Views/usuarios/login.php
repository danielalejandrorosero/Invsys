<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/login.css">
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-boxes"></i>
                </div>
                <h1>Stock Manager</h1>
                <p>Ingrese sus credenciales para acceder</p>
            </div>

            <div class="login-body">
                <form action="../../Controller/usuarios/sesionController.php" method="POST">
                    <div class="form-group">
                        <label for="nombreUsuario">Nombre de Usuario</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese su nombre de usuario" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Recordarme</label>
                        </div>
                        <a href="../../Controller/usuarios/solicitarRecuperacionController.php" class="forgot-password">¿Olvidó su contraseña?</a>
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
            &copy; <?php echo date(
                "Y"
            ); ?> Stock Manager. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
