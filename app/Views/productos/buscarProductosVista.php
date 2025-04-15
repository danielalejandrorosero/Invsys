
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
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
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buscar Productos</h1>

        <!-- Formulario de búsqueda -->
        <form action="../../Controller/productos/buscarProductosController.php" method="GET">

        
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_GET['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($_GET['codigo'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($_GET['sku'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria">
                    <option value="">Todas</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['nombre']); ?>" <?php echo ($_GET['categoria'] ?? '') === $cat['nombre'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="unidad_medida">Unidad de Medida</label>
                <select id="unidad_medida" name="unidad_medida">
                    <option value="">Todas</option>
                    <?php foreach ($unidades_medida as $um): ?>
                        <option value="<?php echo htmlspecialchars($um['nombre']); ?>" <?php echo ($_GET['unidad_medida'] ?? '') === $um['nombre'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($um['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Buscar</button>
        </form>

        <!-- Resultados -->
        <?php if (!empty($productos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>SKU</th>
                        <th>Descripción</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock Mínimo</th>
                        <th>Stock Máximo</th>
                        <th>Categoría</th>
                        <th>Unidad de Medida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($producto['sku']); ?></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio_compra']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio_venta']); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock_minimo']); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock_maximo']); ?></td>
                            <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                            <td><?php echo htmlspecialchars($producto['unidad_medida']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron productos con esos criterios.</p>
        <?php endif; ?>
    </div>
</body>
</html>