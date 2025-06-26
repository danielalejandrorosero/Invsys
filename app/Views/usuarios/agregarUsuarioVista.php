<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </span>
                <p>Complete el formulario para crear un nuevo usuario</p>
                <a href="../../Views/usuarios/dashboard.php" class="btn-floating btn-small waves-effect waves-light red">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="card-content">
                <form action="../../Controller/usuarios/agregarController.php" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="fas fa-user prefix"></i>
                            <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre completo" required>
                            <label for="nombre">Nombre Completo</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="fas fa-user-tag prefix"></i>
                            <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Ingrese nombre de usuario" required>
                            <label for="nombreUsuario">Nombre de Usuario</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="fas fa-envelope prefix"></i>
                            <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
                            <label for="email">Correo Electrónico</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="fas fa-lock prefix"></i>
                            <input type="password" id="password" name="password" placeholder="Ingrese contraseña" required>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </span>
                            <label for="password">Contraseña</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="fas fa-user-shield prefix"></i>
                            <select id="nivel_usuario" name="nivel_usuario" required>
                                <option value="" disabled selected>Seleccione un nivel</option>
                                <option value="1">Administrador</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Operador</option>
                            </select>
                            <label for="nivel_usuario">Nivel de Usuario</label>
                        </div>
                    </div>  

                    <button type="submit" name="agregarUsuario" class="btn waves-effect waves-light green">
                        Crear Usuario <i class="fas fa-user-plus ml-2"></i>
                    </button>
                </form>

                <div class="error-container">
                    <?php if (!empty($error)) {
                        foreach ($error as $err) {
                            echo "<p class='error-message red-text'><i class='fas fa-exclamation-circle'></i> $err</p>";
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            M.FormSelect.init(elems);
        });
    </script>
</body>
</html>
