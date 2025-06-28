<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen de Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/subirImagen.css">
</head>
<body>
<body>
    <div class="container">
        <div class="card-upload">
            <h2>Subir Imagen de Producto</h2>
            <?php if (isset($_SESSION["mensaje"])): ?>
                <div class="message success-message"><?php echo $_SESSION["mensaje"]; ?></div>
                <?php unset($_SESSION["mensaje"]); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["errores"])): ?>
                <?php foreach ($_SESSION["errores"] as $error): ?>
                    <div class="message error-message"><?php echo $error; ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION["errores"]); ?>
            <?php endif; ?>
            <form id="uploadForm" action="../../Controller/subirImagenes/SubirImagenController.php?tipo=producto" method="POST" enctype="multipart/form-data">
                <div class="input-field" style="margin-bottom: 22px;">
                    <select id="id_producto" name="id_producto" required>
                        <option value="" disabled selected>Seleccionar Producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto["id_producto"]; ?>">
                                <?php echo htmlspecialchars($producto["nombre"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="id_producto">Seleccionar Producto</label>
                </div>
                <div class="upload-block">
                    <div class="dropzone" id="dropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Arrastra una imagen aquí<br>o haz clic para seleccionar</p>
                        <input type="file" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="preview-area" id="previewArea">
                        <img id="previewImg" src="" alt="Vista previa">
                        <br>
                        <button type="button" class="remove-btn" onclick="removeFile()">Eliminar imagen</button>
                    </div>
                    <button type="submit" name="subirImagenproducto" class="btn btn-upload" id="submitBtn" disabled>
                        <i class="fas fa-upload"></i> Subir Imagen
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/subirImagen.js"></script>
</body>
</html>
