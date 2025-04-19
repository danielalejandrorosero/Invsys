<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | InvSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f3fa;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header .logo {
            font-size: 48px;
            color: #4a6cf7;
        }

        .login-header h1 {
            margin: 10px 0;
            font-size: 24px;
        }

        .login-header p {
            color: #6c757d;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .input-with-icon input {
            padding-left: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-options .remember-me {
            display: flex;
            align-items: center;
        }

        .form-options .remember-me input {
            margin-right: 5px;
        }

        .form-options .forgot-password {
            color: #4a6cf7;
            text-decoration: none;
        }

        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #4a6cf7;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #3151e4;
        }

        .error-message {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 10px;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>
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
                <h1>InvSys</h1>
                <p>Ingrese sus credenciales para acceder</p>
            </div>

            <div class="login-body">
                <form action="../app/Controller/usuarios/sesionController.php" method="POST">
                    <div class="input-field">
                        <i class="fas fa-user prefix"></i>
                        <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese su nombre de usuario" required>
                        <label for="nombreUsuario">Nombre de Usuario</label>
                    </div>

                    <div class="input-field">
                        <i class="fas fa-lock prefix"></i>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </span>
                        <label for="password">Contraseña</label>
                    </div>

                    <div class="form-options">
                        <label>
                            <input type="checkbox" id="remember" name="remember">
                            <span>Recordarme</span>
                        </label>
                        <a href="../app/Controller/usuarios/solicitarRecuperacionController.php" class="forgot-password">¿Olvidó su contraseña?</a>
                    </div>

                    <button type="submit" name="login" class="btn waves-effect waves-light blue">
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
            ); ?> InvSys. Todos los derechos reservados.
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>