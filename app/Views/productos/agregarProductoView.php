<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/agregarProducto.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-box"></i> Agregar Producto
                </span>
                <p>Complete el formulario para agregar un nuevo producto al inventario</p>
                <a href="../../Views/usuarios/dashboard.php" class="btn-floating btn-small waves-effect waves-light red">
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

                <!-- Progress Bar -->
                <div class="progress">
                    <div class="determinate progress-bar-fill" style="width: 25%"></div>
                </div>

                <!-- Tabs Navigation -->
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s3"><a href="#basic-info" class="active">Información Básica</a></li>
                            <li class="tab col s3"><a href="#details">Detalles</a></li>
                            <li class="tab col s3"><a href="#prices">Precios</a></li>
                            <li class="tab col s3"><a href="#inventory">Inventario</a></li>
                        </ul>
                    </div>
                </div>

                <form action="../../Controller/productos/agregarProductoController.php" method="POST" id="producto-form">
                    <!-- Section 1: Basic Information -->
                    <div id="basic-info" class="form-section active">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-box prefix"></i>
                                <input type="text" id="nombre" name="nombre" class="validate" required>
                                <label for="nombre">Nombre del Producto <span class="required">*</span></label>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-barcode prefix"></i>
                                <input type="text" id="codigo" name="codigo" class="validate" required>
                                <label for="codigo">Código <span class="required">*</span></label>
                                <span class="helper-text" data-error="wrong" data-success="right">El código debe ser único para cada producto</span>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-fingerprint prefix"></i>
                                <input type="text" id="sku" name="sku" class="validate" required>
                                <label for="sku">SKU <span class="required">*</span></label>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-folder prefix"></i>
                                <select id="id_categoria" name="id_categoria" class="validate" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria["id_categoria"]; ?>">
                                            <?php echo htmlspecialchars($categoria["nombre"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_categoria">Categoría <span class="required">*</span></label>
                                <div class="error-message">Debe seleccionar una categoría</div>
                            </div>

                            <div class="input-field col s12">
                                <textarea id="descripcion" name="descripcion" class="materialize-textarea" required></textarea>
                                <label for="descripcion">Descripción <span class="required">*</span></label>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn waves-effect waves-light btn-next right">
                                    Siguiente <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Details -->
                    <div id="details" class="form-section">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-truck prefix"></i>
                                <select id="id_proveedor" name="id_proveedor" class="validate" required>
                                    <option value="" disabled selected>Seleccione un proveedor</option>
                                    <?php foreach ($proveedores as $proveedor): ?>
                                        <option value="<?php echo $proveedor["id_proveedor"]; ?>">
                                            <?php echo htmlspecialchars($proveedor["nombre"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_proveedor">Proveedor <span class="required">*</span></label>
                                <div class="error-message">Debe seleccionar un proveedor</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-ruler prefix"></i>
                                <select id="id_unidad_medida" name="id_unidad_medida" class="validate" required>
                                    <option value="" disabled selected>Seleccione una unidad de medida</option>
                                    <?php foreach ($unidades_medida as $unidad): ?>
                                        <option value="<?php echo $unidad["id_unidad"]; ?>">
                                            <?php echo htmlspecialchars($unidad["nombre"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_unidad_medida">Unidad de Medida <span class="required">*</span></label>
                                <div class="error-message">Debe seleccionar una unidad de medida</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn waves-effect waves-light btn-prev left">
                                    <i class="fas fa-arrow-left"></i> Anterior
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-next right">
                                    Siguiente <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Prices -->
                    <div id="prices" class="form-section">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-tag prefix"></i>
                                <input type="text" id="precio_compra" name="precio_compra" class="validate price-input" required>
                                <label for="precio_compra">Precio de Compra <span class="required">*</span></label>
                                <span class="helper-text" data-error="wrong" data-success="right">$</span>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-dollar-sign prefix"></i>
                                <input type="text" id="precio_venta" name="precio_venta" class="validate price-input" required>
                                <label for="precio_venta">Precio de Venta <span class="required">*</span></label>
                                <span class="helper-text" data-error="wrong" data-success="right">$</span>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn waves-effect waves-light btn-prev left">
                                    <i class="fas fa-arrow-left"></i> Anterior
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-next right">
                                    Siguiente <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Inventory -->
                    <div id="inventory" class="form-section">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-level-down-alt prefix"></i>
                                <input type="number" id="stock_minimo" name="stock_minimo" class="validate" required>
                                <label for="stock_minimo">Stock Mínimo <span class="required">*</span></label>
                                <span class="helper-text" data-error="wrong" data-success="right">Nivel mínimo para recibir alertas de stock bajo</span>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="fas fa-level-up-alt prefix"></i>
                                <input type="number" id="stock_maximo" name="stock_maximo" class="validate" required>
                                <label for="stock_maximo">Stock Máximo <span class="required">*</span></label>
                                <div class="error-message">Este campo es obligatorio</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn waves-effect waves-light btn-prev left">
                                    <i class="fas fa-arrow-left"></i> Anterior
                                </button>
                                <button type="submit" name="agregarProducto" class="btn waves-effect waves-light right">
                                    <i class="fas fa-plus-circle"></i> Agregar Producto
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/agregarProducto.js"></script>
</body>
</html>