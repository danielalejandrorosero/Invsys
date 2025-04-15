<!DOCTYPE html>
<html>
<head>
    <title>Transferir Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            box-sizing: border-box;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        select, input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .hidden-input, .static-value {
            display: none;
        }
        .static-value {
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transferir Stock</h1>
        <form action="../../Controller/stock/transferirStock.php" method="POST">
            <label for="id_producto">Producto:</label>
            <select id="id_producto" name="id_producto" onchange="this.form.submit()" required>
                <option value="">Seleccione un producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?php echo $producto['id_producto']; ?>" <?php if (isset($id_producto) && $id_producto == $producto['id_producto']) echo 'selected'; ?>><?php echo $producto['nombre']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="obtenerAlmacenOrigen" value="1">
        </form>

        <?php if (isset($almacen_origen)): ?>
        <form action="../../Controller/stock/transferirStock.php" method="POST">
            <label for="id_producto">Producto:</label>
            <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>" required>
            <span class="static-value"><?php echo $productos[array_search($id_producto, array_column($productos, 'id_producto'))]['nombre']; ?></span><br>

            <label for="id_almacen_origen">Almacén Origen:</label>
            <input type="hidden" id="id_almacen_origen" name="id_almacen_origen" value="<?php echo $almacen_origen['id_almacen']; ?>" required>
            <span class="static-value"><?php echo $almacen_origen['nombre']; ?></span><br>

            <label for="id_almacen_destino">Almacén Destino:</label>
            <select id="id_almacen_destino" name="id_almacen_destino" required>
                <?php foreach ($almacenes as $almacen): ?>
                    <option value="<?php echo $almacen['id_almacen']; ?>"><?php echo $almacen['nombre']; ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" required><br>

            <input type="submit" name="transferirStock" value="Transferir Stock">
        </form>
        <?php endif; ?>
    </div>
</body>
</html>