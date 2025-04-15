<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Producto</title>
    <link rel="stylesheet" href="../../../frontend/transferirStock.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Seleccionar Producto</h1>
        <form action="../../Controller/stock/transferirStock.php" method="post">
        <div class="form-group">
                <label for="id_producto">Producto</label>
                <select id="id_producto" name="id_producto" required>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= htmlspecialchars($producto['id_producto']) ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Seleccionar</button>
        </form>
        <?php
        if (!empty($_SESSION['error'])) {
            echo "<p style='color:red;'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>
</html>