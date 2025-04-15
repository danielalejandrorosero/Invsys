<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../frontend/stock.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
    <title>Ver Inventario</title>
</head>
<body>
    <h1>Ver Inventario</h1>
    <form action="../../Controller/stock/verInventarioController.php" method="post">
        <div class="form-group">
            <label for="id_almacen">Almacén</label>
            <select id="id_almacen" name="id_almacen" required>
                <?php foreach ($almacenes as $almacen): ?>
                    <option value="<?php echo htmlspecialchars($almacen['id_almacen']); ?>">
                        <?php echo htmlspecialchars($almacen['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="ver_inventario">Ver Inventario</button>
    </form>
</body>
</html>

