<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Inventario</title>
</head>
<body>
    <h1>Inventario del Almacén</h1>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inventario)): ?>
                <?php foreach ($inventario as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['producto']); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No hay productos en este almacén.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>