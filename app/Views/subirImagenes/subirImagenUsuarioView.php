
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen de Usuario</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
</head>
<body>
    <div class="container">
        <h1>Subir Imagen de Usuario</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <p style="color: green;"><?php echo $_SESSION['mensaje']; ?></p>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errores'])): ?>
            <?php foreach ($_SESSION['errores'] as $error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>

        <form action="../../Controller/subirImagenes/SubirImagenController.php?tipo=usuario" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imagen">Seleccionar Imagen</label>
                <input type="file" id="imagen" name="imagen" required>
            </div>
            <button type="submit" name="subirImagenusuario">Subir Imagen</button>
        </form>
    </div>
</body>
</html>