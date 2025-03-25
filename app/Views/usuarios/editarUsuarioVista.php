<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../../frontend/editarUsuario.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        <form action="../../Controller/usuarios/editarUsuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['id_usuario']); ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($usuario['nombreUsuario'] ?? ''); ?>" required>
                <?php if (isset($_SESSION['error_nombreUsuario'])): ?>
                    <p style="color: red;"> <?php echo $_SESSION['error_nombreUsuario']; unset($_SESSION['error_nombreUsuario']); ?> </p>
                <?php endif; ?>
            </div>
            <button type="submit" name="actualizarUsuario">Actualizar Usuario</button>
        </form>
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<p style='color:green;'>{$_SESSION['mensaje']}</p>";
            unset($_SESSION['mensaje']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red;'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>
