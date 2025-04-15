<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de stock</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            padding: 5px;
            margin-right: 5px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Reporte de Stock</h2>

<form method="GET">
    <input type="text" name="almacen" placeholder="Buscar por almacén" value="<?= isset($_GET['almacen']) ? htmlspecialchars($_GET['almacen']) : '' ?>">
    <input type="text" name="categoria" placeholder="Buscar por categoría" value="<?= isset($_GET['categoria']) ? htmlspecialchars($_GET['categoria']) : '' ?>">
    <input type="text" name="proveedor" placeholder="Buscar por proveedor" value="<?= isset($_GET['proveedor']) ? htmlspecialchars($_GET['proveedor']) : '' ?>">
    <button type="submit">Buscar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Almacén</th>
            <th>Cantidad</th>
            <th>Stock Mínimo</th>
            <th>Stock Máximo</th>
            <th>Categoría</th>
            <th>Proveedor</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['producto']) ?></td>
                    <td><?= htmlspecialchars($row['almacen']) ?></td>
                    <td><?= htmlspecialchars($row['cantidad']) ?></td>
                    <td><?= htmlspecialchars($row['stock_minimo']) ?></td>
                    <td><?= htmlspecialchars($row['stock_maximo']) ?></td>
                    <td><?= htmlspecialchars($row['categoria']) ?></td>
                    <td><?= htmlspecialchars($row['proveedor']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No se encontraron resultados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>