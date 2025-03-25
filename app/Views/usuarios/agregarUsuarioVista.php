<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../../../frontend/agregarUsuario.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Agregar Usuario</h1>
        <form action="../../Controller/usuarios/agregarController.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="nivel_usuario">Nivel de Usuario</label>
                <select id="nivel_usuario" name="nivel_usuario" required>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario Regular</option>
                    <!-- Añadir más niveles según sea necesario -->
                </select>
            </div>
            <button type="submit" name="agregarUsuario">Agregar Usuario</button>
        </form>
        <?php
        // Mostrar errores acumulados
        if (!empty($error)) {
            foreach ($error as $err) {
                echo "<p style='color:red;'>$err</p>";
            }
        }
        ?>
    </div>
</body>
</html>