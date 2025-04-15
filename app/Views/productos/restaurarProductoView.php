<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/restaurarProducto.css">

</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-icon">
                    <i class="fas fa-trash-restore"></i>
                </div>
                <h1>Restaurar Producto</h1>
                <p>Está a punto de restaurar un producto previamente eliminado</p>
            </div>

            <div class="card-body">
                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($_SESSION["errores"] as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION["errores"]); ?>
                <?php endif; ?>

                <div class="product-info">
                    <div class="product-name">
                        <?php echo htmlspecialchars($producto["nombre"]); ?>
                    </div>
                    <div class="product-details">
                        Código: <?php echo htmlspecialchars(
                            $producto["codigo"] ?? "N/A"
                        ); ?> |
                        SKU: <?php echo htmlspecialchars(
                            $producto["sku"] ?? "N/A"
                        ); ?>
                    </div>
                </div>

                <p class="confirmation-text">
                    ¿Está seguro que desea <strong>restaurar</strong> este producto?<br>
                    El producto volverá a estar disponible en el inventario activo.
                </p>

                <form method="POST">
                    <input type="hidden" name="confirmarRestaurar" value="1">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-trash-restore"></i> Restaurar Producto
                        </button>
                        <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>

                <div class="note-box">
                    <i class="fas fa-info-circle"></i>
                    <div class="note-content">
                        Al restaurar este producto, se recuperarán sus datos pero deberá verificar el stock actual y actualizar los valores según sea necesario.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
