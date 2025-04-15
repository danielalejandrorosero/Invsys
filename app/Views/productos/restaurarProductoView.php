<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurar Producto</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
</head>
<body>
    <div class="container">
        <h1>Confirmar Restauración</h1>

        <?php if (isset($_SESSION["errores"])): ?>
            <div class="mensaje-error">
                <?php foreach ($_SESSION["errores"] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION["errores"]); ?>
            </div>
        <?php endif; ?>

        <p>¿Está seguro que desea restaurar el producto <strong><?php echo htmlspecialchars(
            $producto["nombre"]
        ); ?></strong>?</p>

        <form method="POST">
            <input type="hidden" name="confirmarRestaurar" value="1">
            <button type="submit" class="boton boton-agregar">Restaurar</button>
            <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="boton">Cancelar</a>
        </form>
    </div>
</body>
</html>
