<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Stock Bajo</title>
    <link rel="stylesheet" href="../frontend/alertarStock.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
    <script>
        function mostrarAlerta(productos) {
            let mensaje = "Los siguientes productos tienen stock bajo:\n";
            productos.forEach(producto => {
                mensaje += `${producto.nombre} (ID: ${producto.id_producto}): ${producto.cantidad_disponible} unidades disponibles, mínimo requerido: ${producto.stock_minimo} unidades.\n`;
            });
            alert(mensaje);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Productos con Stock Bajo</h1>
        <?php if (!empty($productosBajoStock)): ?>
            <script>
                const productos = <?php echo json_encode($productosBajoStock); ?>;
                mostrarAlerta(productos);
            </script>
        <?php else: ?>
            <p>No hay productos con stock bajo.</p>
        <?php endif; ?>
    </div>
</body>
</html>