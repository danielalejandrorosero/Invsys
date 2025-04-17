<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen de Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <div class="container">
        <h1 class="center-align">Subir Imagen de Usuario</h1>

        <?php if (isset($_SESSION["mensaje"])): ?>
            <p class="green-text"><?php echo $_SESSION["mensaje"]; ?></p>
            <?php unset($_SESSION["mensaje"]); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION["errores"])): ?>
            <?php foreach ($_SESSION["errores"] as $error): ?>
                <p class="red-text"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION["errores"]); ?>
        <?php endif; ?>

        <form action="../../Controller/subirImagenes/SubirImagenController.php?tipo=usuario" method="POST" enctype="multipart/form-data">
            <div class="file-field input-field">
                <div class="btn">
                    <span>Seleccionar Imagen</span>
                    <input type="file" id="imagen" name="imagen" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="center-align">
                <button type="submit" name="subirImagenusuario" class="btn waves-effect waves-light">
                    Subir Imagen
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
