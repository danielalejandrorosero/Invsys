
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustar Inventario</title>
    <link rel="stylesheet" href="../../../frontend/ajustarStock.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <h2>Ajustar Inventario</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form action="../../Controller/stock/ajustarStockController.php" method="post">
        <label for="id_producto">Producto:</label>
        <select name="id_producto" required>
            <?php foreach ($productos as $producto): ?>
                <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="seleccionar_producto">Seleccionar Producto</button>
    </form>

    <?php if (!empty($almacenes)): ?>
        <form action="" method="post">
            <input type="hidden" name="id_producto" value="<?php echo isset($id_producto) ? $id_producto : ''; ?>">
            <label for="id_almacen">Almacén:</label>
            <select name="id_almacen" required>
                <?php foreach ($almacenes as $almacen): ?>
                    <option value="<?php echo $almacen['id_almacen']; ?>"><?php echo $almacen['nombre']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="cantidad">Nueva cantidad:</label>
            <input type="number" name="cantidad" min="0" required>

            <button type="submit" name="ajustar_stock">Actualizar Stock</button>
        </form>
    <?php endif; ?>
</body>
</html>



















