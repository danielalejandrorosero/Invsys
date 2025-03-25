<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../../../frontend/solicitarRecuperacion.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Recuperar Contraseña</h1>
        <form action="../../Controller/usuarios/solicitarRecuperacionController.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <button type="submit">Enviar Correo de Recuperación</button>
        </form>
        <?php if (!empty($mensaje)): ?>
            <p style="color: green;"><?php echo $mensaje; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>