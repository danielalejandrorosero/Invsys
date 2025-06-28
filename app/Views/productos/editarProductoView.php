<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-box"></i> Editar Producto
                </span>
                <a href="../../Controller/productos/buscarProductosController.php" class="btn-floating btn-small waves-effect waves-light red">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="card-content">
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="card-panel green lighten-4 green-text text-darken-4">
                        <i class="fas fa-check-circle"></i>
                        <div><?php echo $_SESSION["mensaje"]; ?></div>
                    </div>
                    <?php unset($_SESSION["mensaje"]); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="card-panel red lighten-4 red-text text-darken-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($_SESSION["errores"] as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION["errores"]); ?>
                <?php endif; ?>

                <form action="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                    "id_producto"
                ]; ?>" method="POST">
                    <!-- Basic Information Section -->
                    <div class="section">
                        <h5><i class="fas fa-info-circle"></i> Información Básica</h5>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-tag prefix"></i>
                                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars(
                                    $producto["nombre"]
                                ); ?>" required>
                                <label for="nombre">Nombre <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-barcode prefix"></i>
                                <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars(
                                    $producto["codigo"]
                                ); ?>" required>
                                <label for="codigo">Código <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-fingerprint prefix"></i>
                                <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars(
                                    $producto["sku"]
                                ); ?>" required>
                                <label for="sku">SKU <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-folder prefix"></i>
                                <select id="id_categoria" name="id_categoria" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    <?php foreach (
                                        $categorias
                                        as $categoria
                                    ): ?>
                                        <option value="<?php echo $categoria[
                                            "id_categoria"
                                        ]; ?>" <?php echo $categoria[
    "id_categoria"
] == $producto["id_categoria"]
    ? "selected"
    : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $categoria["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_categoria">Categoría <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12">
                                <textarea id="descripcion" name="descripcion" class="materialize-textarea" required><?php echo htmlspecialchars(
                                    $producto["descripcion"]
                                ); ?></textarea>
                                <label for="descripcion">Descripción <span class="required">*</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Section -->
                    <div class="section">
                        <h5><i class="fas fa-dollar-sign"></i> Precios</h5>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-tag prefix"></i>
                                <input type="text" id="precio_compra" name="precio_compra" class="price-input" value="<?php echo htmlspecialchars(
                                    $producto["precio_compra"]
                                ); ?>" required>
                                <label for="precio_compra">Precio de Compra <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-dollar-sign prefix"></i>
                                <input type="text" id="precio_venta" name="precio_venta" class="price-input" value="<?php echo htmlspecialchars(
                                    $producto["precio_venta"]
                                ); ?>" required>
                                <label for="precio_venta">Precio de Venta <span class="required">*</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Supplier Section -->
                    <div class="section">
                        <h5><i class="fas fa-boxes"></i> Inventario y Proveedores</h5>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-level-down-alt prefix"></i>
                                <input type="number" id="stock_minimo" name="stock_minimo" value="<?php echo htmlspecialchars(
                                    $producto["stock_minimo"]
                                ); ?>" min="0" required>
                                <label for="stock_minimo">Stock Mínimo <span class="required">*</span></label>
                                <span class="helper-text">Nivel mínimo para recibir alertas de stock bajo</span>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-level-up-alt prefix"></i>
                                <input type="number" id="stock_maximo" name="stock_maximo" value="<?php echo htmlspecialchars(
                                    $producto["stock_maximo"]
                                ); ?>" min="0" required>
                                <label for="stock_maximo">Stock Máximo <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-truck prefix"></i>
                                <select id="id_proveedor" name="id_proveedor" required>
                                    <option value="" disabled selected>Seleccione un proveedor</option>
                                    <?php foreach (
                                        $proveedores
                                        as $proveedor
                                    ): ?>
                                        <option value="<?php echo $proveedor[
                                            "id_proveedor"
                                        ]; ?>" <?php echo $proveedor[
    "id_proveedor"
] == $producto["id_proveedor"]
    ? "selected"
    : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $proveedor["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_proveedor">Proveedor <span class="required">*</span></label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-ruler prefix"></i>
                                <select id="id_unidad_medida" name="id_unidad_medida" required>
                                    <option value="" disabled selected>Seleccione una unidad de medida</option>
                                    <?php foreach (
                                        $unidades_medida
                                        as $unidad
                                    ): ?>
                                        <option value="<?php echo $unidad[
                                            "id_unidad"
                                        ]; ?>" <?php echo $unidad[
    "id_unidad"
] == $producto["id_unidad_medida"]
    ? "selected"
    : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $unidad["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_unidad_medida">Unidad de Medida <span class="required">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <a href="../../Controller/productos/eliminarProductoController.php?id=<?php echo $producto[
                                "id_producto"
                            ]; ?>" class="btn red" id="deleteBtn">
                                <i class="fas fa-trash-alt"></i> Eliminar Producto
                            </a>
                            <a href="../../Controller/productos/buscarProductosController.php" class="btn grey" id="cancelBtn">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" name="editarProducto" class="btn blue">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/editarProducto.js"></script>
</body>
</html>