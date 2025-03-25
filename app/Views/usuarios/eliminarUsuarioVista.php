<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="../../../frontend/eliminarUsuario.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Eliminar Usuario</h1>
        <form action="../../Controller/usuarios/eliminarUsuarioController.php" method="POST">
            <div class="form-group">
                <label for="id_usuario">Seleccionar Usuario</label>
                <select id="id_usuario" name="id_usuario" required>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo htmlspecialchars($usuario['nombreUsuario']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="eliminarUsuario">Eliminar Usuario</button>
        </form>
        <?php
        // Mostrar mensajes si los hay
        if (isset($mensaje)) {
            echo "<p style='color:green;'>$mensaje</p>";
        }
        if (!empty($error)) {
            foreach ($error as $err) {
                echo "<p style='color:red;'>$err</p>";
            }
        }
        ?>
    </div>
</body>
</html>