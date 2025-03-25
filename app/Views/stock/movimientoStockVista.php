<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos de Stock</title>
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

<h2>Historial de Movimientos de Stock</h2>

<form method="GET">
    <input type="text" name="almacen" placeholder="Buscar por almacén" value="<?= isset($_GET['almacen']) ? htmlspecialchars($_GET['almacen']) : '' ?>">
    <input type="text" name="producto" placeholder="Buscar por producto" value="<?= isset($_GET['producto']) ? htmlspecialchars($_GET['producto']) : '' ?>">
    <input type="text" name="tipo" placeholder="Buscar por tipo" value="<?= isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : '' ?>">
    <button type="submit">Buscar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Almacén Origen</th>
            <th>Almacén Destino</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $resultados->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['producto']) ?></td>
                <td><?= htmlspecialchars($row['tipo_movimiento']) ?></td>
                <td><?= htmlspecialchars($row['cantidad']) ?></td>
                <td><?= htmlspecialchars($row['fecha_movimiento']) ?></td>
                <td><?= htmlspecialchars($row['almacen_origen']) ?></td>
                <td><?= htmlspecialchars($row['almacen_destino']) ?></td>
                <td><?= htmlspecialchars($row['usuario']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
