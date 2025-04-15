<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen de Producto</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
    <link rel="stylesheet" href="../../../public/css/subirimagenproducto.css">
</head>
<body>
    <div class="container">
        <h1>Subir Imagen de Producto</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <p class="alert alert-success"><?php echo $_SESSION['mensaje']; ?></p>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errores'])): ?>
            <?php foreach ($_SESSION['errores'] as $error): ?>
                <p class="alert alert-danger"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>
        <form action="../../Controller/subirImagenes/SubirImagenController.php?tipo=producto" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="id_producto">Seleccionar Producto</label>
                <select id="id_producto" name="id_producto" required>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>">
                            <?php echo htmlspecialchars($producto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagen">Seleccionar Imagen</label>
                <input type="file" id="imagen" name="imagen" required>
            </div>
            <button type="submit" name="subirImagenproducto">Subir Imagen</button>
        </form>
    </div>
</body>
</html>