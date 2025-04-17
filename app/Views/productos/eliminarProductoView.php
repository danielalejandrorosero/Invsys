<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Confirmar Eliminación</span>

                <?php if (isset($_SESSION["errores"])): ?>
                    <?php foreach ($_SESSION["errores"] as $error): ?>
                        <p class="red-text"><?php echo $error; ?></p>
                    <?php endforeach; ?>
                    <?php unset($_SESSION["errores"]); ?>
                <?php endif; ?>

                <p>¿Está seguro que desea eliminar el producto <strong><?php echo htmlspecialchars(
                    $producto["nombre"]
                ); ?></strong>?</p>

                <form method="POST">
                    <input type="hidden" name="confirmarEliminar" value="1">
                    <button type="submit" class="btn red">Eliminar</button>
                    <a href="../../Controller/productos/ListarProductosController.php" class="btn grey">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
