
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Producto</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <p style="color: green;"><?php echo $_SESSION['mensaje']; ?></p>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errores'])): ?>
            <?php foreach ($_SESSION['errores'] as $error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>

        <form action="../../Controller/productos/agregarProductoController.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="precio_compra">Precio Compra</label>
                <input type="text" id="precio_compra" name="precio_compra" required>
            </div>
            <div class="form-group">
                <label for="precio_venta">Precio Venta</label>
                <input type="text" id="precio_venta" name="precio_venta" required>
            </div>
            <div class="form-group">
                <label for="stock_minimo">Stock Mínimo</label>
                <input type="number" id="stock_minimo" name="stock_minimo" required>
            </div>
            <div class="form-group">
                <label for="stock_maximo">Stock Máximo</label>
                <input type="number" id="stock_maximo" name="stock_maximo" required>
            </div>
            <div class="form-group">
                <label for="id_categoria">Categoría</label>
                <select id="id_categoria" name="id_categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_unidad_medida">Unidad de Medida</label>
                <select id="id_unidad_medida" name="id_unidad_medida" required>
                    <option value="">Seleccione una unidad de medida</option>
                    <?php foreach ($unidades_medida as $unidad): ?>
                        <option value="<?php echo $unidad['id_unidad']; ?>">
                            <?php echo htmlspecialchars($unidad['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_proveedor">Proveedor</label>
                <select id="id_proveedor" name="id_proveedor" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?php echo $proveedor['id_proveedor']; ?>">
                            <?php echo htmlspecialchars($proveedor['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>  
            <button type="submit" name="agregarProducto">Agregar Producto</button>
        </form>
    </div>
</body>
</html>