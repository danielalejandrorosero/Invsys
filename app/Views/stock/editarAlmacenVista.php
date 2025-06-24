<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Almacén</title>
    <link rel="stylesheet" href="../../../public/css/editarAlmacen.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-warehouse"></i> Editar Almacén</h1>
        <?php if(isset($error) && $error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if(isset($success) && $success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="" method="post" class="form">
            <div class="form-group">
                <label for="nombre">Nombre del almacén</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($almacen['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación</label>
                <input type="text" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($almacen['ubicacion']); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="editar_almacen" class="btn btn-save"><i class="fa-solid fa-save"></i> Guardar Cambios</button>
                <a href="../stock/ListarAlmacenesController.php" class="btn btn-cancel"><i class="fa-solid fa-arrow-left"></i> Volver</a>
            </div>
        </form>
    </div>
</body>
</html>