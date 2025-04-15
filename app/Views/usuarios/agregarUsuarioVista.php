    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Usuario | Stock Manager</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../../../public/css/agregarUsuario.css">


        <script>
            function togglePassword() {
                const passwordField = document.getElementById('password');
                const icon = document.querySelector('.password-toggle i');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-e  ye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const errorContainer = document.querySelector('.error-container');

                // Check if there are error messages and show the container
                const errorMessages = document.querySelectorAll('.error-message');
                if (errorMessages.length > 0) {
                    errorContainer.classList.add('active');
                }
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="form-header">
                <a href="../../Views/usuarios/index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1>Agregar Usuario</h1>
            <p>Complete el formulario para crear un nuevo usuario</p>
        </div>

        <div class="form-body">
            <form action="../../Controller/usuarios/agregarController.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre completo" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombreUsuario">Nombre de Usuario</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tag"></i>
                        <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese nombre de usuario" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Ingrese contraseña" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nivel_usuario">Nivel de Usuario</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-shield"></i>
                        <select id="nivel_usuario" name="nivel_usuario" required>
                            <option value="" disabled selected>Seleccione un nivel</option>
                            <option value="1">Administrador</option>
                            <option value="2">Usuario Regular</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="agregarUsuario">
                    Crear Usuario <i class="fas fa-user-plus ml-2"></i>
                </button>
            </form>

            <div class="error-container">
                <?php // Mostrar errores acumulados
// Mostrar errores acumulados
// Mostrar errores acumulados
                // Mostrar errores acumulados
                // Mostrar errores acumulados
                // Mostrar errores acumulados
                // Mostrar errores acumulados
                // Mostrar errores acumulados
                // Mostrar errores acumulados
                if (!empty($error)) {
                    foreach ($error as $err) {
                        echo "<p class='error-message'><i class='fas fa-exclamation-circle'></i> $err</p>";
                    }
                } ?>
            </div>
        </div>
    </div>
</body>
</html>
