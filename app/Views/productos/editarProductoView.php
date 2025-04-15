<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/buscarProducto.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Price input formatting
            const priceInputs = document.querySelectorAll('.price-input');

            priceInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    // Remove non-numeric characters except decimal point
                    let value = this.value.replace(/[^\d.]/g, '');

                    // Ensure only one decimal point
                    const decimalCount = (value.match(/\./g) || []).length;
                    if (decimalCount > 1) {
                        const parts = value.split('.');
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }

                    // Format with two decimal places
                    if (value.includes('.')) {
                        const parts = value.split('.');
                        if (parts[1].length > 2) {
                            parts[1] = parts[1].substring(0, 2);
                            value = parts.join('.');
                        }
                    }

                    this.value = value;
                });
            });

            // Confirmation for cancellation
            const cancelBtn = document.getElementById('cancelBtn');

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    if (!confirm('¿Está seguro que desea cancelar la edición? Los cambios no guardados se perderán.')) {
                        e.preventDefault();
                    }
                });
            }

            // Confirmation for deletion
            const deleteBtn = document.getElementById('deleteBtn');

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    if (!confirm('¿Está seguro que desea eliminar este producto? Esta acción no se puede deshacer.')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="product-image">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h1><?php echo htmlspecialchars(
                            $producto["nombre"]
                        ); ?></h1>
                        <div class="product-id">ID: <?php echo htmlspecialchars(
                            $producto["id_producto"]
                        ); ?></div>
                    </div>
                </div>
                <a href="../../Controller/productos/buscarProductosController.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            <div class="card-body">
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div><?php echo $_SESSION["mensaje"]; ?></div>
                    </div>
                    <?php unset($_SESSION["mensaje"]); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="alert alert-danger">
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
                        <h2 class="section-title"><i class="fas fa-info-circle"></i> Información Básica</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-tag input-icon"></i>
                                    <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars(
                                        $producto["nombre"]
                                    ); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="codigo" class="form-label">Código <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-barcode input-icon"></i>
                                    <input type="text" id="codigo" name="codigo" class="form-control" value="<?php echo htmlspecialchars(
                                        $producto["codigo"]
                                    ); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sku" class="form-label">SKU <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-fingerprint input-icon"></i>
                                    <input type="text" id="sku" name="sku" class="form-control" value="<?php echo htmlspecialchars(
                                        $producto["sku"]
                                    ); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="id_categoria" class="form-label">Categoría <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-folder input-icon"></i>
                                    <select id="id_categoria" name="id_categoria" class="form-control" required>
                                        <option value="">Seleccione una categoría</option>
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
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label for="descripcion" class="form-label">Descripción <span class="required">*</span></label>
                                <textarea id="descripcion" name="descripcion" class="form-control" required><?php echo htmlspecialchars(
                                    $producto["descripcion"]
                                ); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Section -->
                    <div class="section">
                        <h2 class="section-title"><i class="fas fa-dollar-sign"></i> Precios</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="precio_compra" class="form-label">Precio de Compra <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-tag input-icon"></i>
                                    <input type="text" id="precio_compra" name="precio_compra" class="form-control price-input" value="<?php echo htmlspecialchars(
                                        $producto["precio_compra"]
                                    ); ?>" required>
                                    <span class="input-addon">$</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="precio_venta" class="form-label">Precio de Venta <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-dollar-sign input-icon"></i>
                                    <input type="text" id="precio_venta" name="precio_venta" class="form-control price-input" value="<?php echo htmlspecialchars(
                                        $producto["precio_venta"]
                                    ); ?>" required>
                                    <span class="input-addon">$</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory & Supplier Section -->
                    <div class="section">
                        <h2 class="section-title"><i class="fas fa-boxes"></i> Inventario y Proveedores</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="stock_minimo" class="form-label">Stock Mínimo <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-level-down-alt input-icon"></i>
                                    <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" value="<?php echo htmlspecialchars(
                                        $producto["stock_minimo"]
                                    ); ?>" min="0" required>
                                </div>
                                <div class="form-info">
                                    <i class="fas fa-info-circle"></i> Nivel mínimo para recibir alertas de stock bajo
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="stock_maximo" class="form-label">Stock Máximo <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-level-up-alt input-icon"></i>
                                    <input type="number" id="stock_maximo" name="stock_maximo" class="form-control" value="<?php echo htmlspecialchars(
                                        $producto["stock_maximo"]
                                    ); ?>" min="0" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="id_proveedor" class="form-label">Proveedor <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-truck input-icon"></i>
                                    <select id="id_proveedor" name="id_proveedor" class="form-control" required>
                                        <option value="">Seleccione un proveedor</option>
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
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="id_unidad_medida" class="form-label">Unidad de Medida <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-ruler input-icon"></i>
                                    <select id="id_unidad_medida" name="id_unidad_medida" class="form-control" required>
                                        <option value="">Seleccione una unidad de medida</option>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div>
                            <a href="../../Controller/productos/eliminarProductoController.php?id=<?php echo $producto[
                                "id_producto"
                            ]; ?>" class="btn btn-danger" id="deleteBtn">
                                <i class="fas fa-trash-alt"></i> Eliminar Producto
                            </a>
                        </div>
                        <div class="action-right">
                            <a href="../../Controller/productos/buscarProductosController.php" class="btn btn-secondary" id="cancelBtn">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" name="editarProducto" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
