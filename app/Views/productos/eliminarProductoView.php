<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
</head>
<body>
    <div class="container">
        <h1>Confirmar Eliminación</h1>

        <?php if (isset($_SESSION["errores"])): ?>
            <?php foreach ($_SESSION["errores"] as $error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION["errores"]); ?>
        <?php endif; ?>

        <p>¿Está seguro que desea eliminar el producto <strong><?php echo htmlspecialchars(
            $producto["nombre"]
        ); ?></strong>?</p>

        <form method="POST">
            <input type="hidden" name="confirmarEliminar" value="1">
            <button type="submit" class="btn-danger">Eliminar</button>
            <a href="../../Controller/productos/ListarProductosController.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>
