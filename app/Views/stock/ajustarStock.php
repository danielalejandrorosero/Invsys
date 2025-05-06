<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustar Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        .header-content h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .header-content p {
            margin: 0;
            color: #757575;
        }
        .form-section {
            margin-top: 20px;
        }
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-group i {
            margin-right: 10px;
        }
        .input-suffix {
            margin-left: 10px;
        }
        .form-hint {
            font-size: 0.875rem;
            color: #757575;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .alert {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #e0f7fa;
            border: 1px solid #b2ebf2;
            border-radius: 4px;
            margin-top: 20px;
        }
        .alert i {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .alert-info {
            background-color: #e0f7fa;
            border-color: #b2ebf2;
        }
        .product-info {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        .product-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        .product-details h3 {
            margin: 0;
            font-size: 1.25rem;
        }
        .product-details p {
            margin: 0;
            color: #757575;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus the first select when the page loads
            const firstSelect = document.querySelector('select');
            if (firstSelect) {
                firstSelect.focus();
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="header-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="header-content">
                            <h1>Ajustar Inventario</h1>
                            <p>Actualizar la cantidad de productos en stock</p>
                        </div>
                    </div>
                </div>

                <!-- Error messages -->
                <?php if (isset($_SESSION["error"])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div><?php
                        echo $_SESSION["error"];
                        unset($_SESSION["error"]);
                        ?></div>
                    </div>
                <?php endif; ?>

                <!-- First form: Select product -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-box-open"></i> Paso 1: Seleccionar Producto
                    </h2>

                    <form action="../../Controller/stock/ajustarStockController.php" method="post">
                        <div class="input-field">
                            <i class="fas fa-search prefix"></i>
                            <select name="id_producto" id="id_producto" required>
                                <option value="" disabled selected>Seleccione un producto</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto[
                                        "id_producto"
                                    ]; ?>"
                                        <?php echo isset($id_producto) &&
                                        $id_producto == $producto["id_producto"]
                                            ? "selected"
                                            : ""; ?>>
                                        <?php echo htmlspecialchars(
                                            $producto["nombre"]
                                        ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="id_producto">Producto:</label>
                            <span class="helper-text">Seleccione el producto cuyo inventario desea ajustar</span>
                        </div>

                        <div class="form-actions">
                            <a href="../../Controller/stock/verInventarioController.php" class="btn grey">
                                <i class="fas fa-arrow-left"></i> Volver al Inventario
                            </a>
                            <button type="submit" name="seleccionar_producto" class="btn blue">
                                <i class="fas fa-check"></i> Seleccionar Producto
                            </button>
                        </div>
                    </form>
                </div>

                <?php if (!empty($almacenes) && isset($id_producto)):
                    // Find the selected product information


                    $productoSeleccionado = null;
                    foreach ($productos as $producto) {
                        if ($producto["id_producto"] == $id_producto) {
                            $productoSeleccionado = $producto;
                            break;
                        }
                    }
                    ?>
                    <!-- Product info if available -->
                    <?php if ($productoSeleccionado): ?>
                        <div class="product-info">
                            <div class="product-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="product-details">
                                <h3><?php echo htmlspecialchars(
                                    $productoSeleccionado["nombre"]
                                ); ?></h3>
                                <p>
                                    <?php if (
                                        isset($productoSeleccionado["codigo"])
                                    ): ?>
                                        Código: <?php echo htmlspecialchars(
                                            $productoSeleccionado["codigo"]
                                        ); ?> |
                                    <?php endif; ?>
                                    <?php if (
                                        isset($productoSeleccionado["sku"])
                                    ): ?>
                                        SKU: <?php echo htmlspecialchars(
                                            $productoSeleccionado["sku"]
                                        ); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Second form: Adjust stock -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-warehouse"></i> Paso 2: Ajustar Cantidad
                        </h2>

                        <form action="" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo isset($id_producto) ? $id_producto : ""; ?>">

                            <div class="input-field">
                                <i class="fas fa-building prefix"></i>
                                <select name="id_almacen" id="id_almacen" required>
                                    <option value="" disabled selected>Seleccione un almacén</option>
                                    <?php foreach ($almacenes as $almacen): ?>
                                        <option value="<?php echo $almacen["id_almacen"]; ?>">
                                            <?php echo htmlspecialchars($almacen["nombre"]); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_almacen">Almacén:</label>
                                <span class="helper-text">Seleccione el almacén donde desea ajustar el inventario</span>
                            </div>

                            <div class="input-field">
                                <p>
                                    <label>
                                        <input name="tipo_ajuste" type="radio" value="absoluto" checked />
                                        <span>Establecer cantidad absoluta</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="tipo_ajuste" type="radio" value="incremento" />
                                        <span>Incrementar stock</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="tipo_ajuste" type="radio" value="decremento" />
                                        <span>Disminuir stock</span>
                                    </label>
                                </p>
                            </div>

                            <div class="input-field">
                                <i class="fas fa-hashtag prefix"></i>
                                <input type="number" id="cantidad" name="cantidad" min="0" required>
                                <label for="cantidad">Cantidad:</label>
                                <span class="helper-text" id="cantidad-helper">Ingrese la nueva cantidad total de este producto en el almacén seleccionado</span>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <div id="ajuste-info">
                                    <strong>Nota:</strong> Este ajuste sobrescribirá la cantidad actual del producto en el almacén seleccionado.
                                    Para aumentar o disminuir el stock de forma relativa, utilice la sección de movimientos.
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" onclick="history.back()" class="btn grey">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" name="ajustar_stock" class="btn green">
                                    <i class="fas fa-save"></i> Actualizar Stock
                                </button>
                            </div>
                        </form>
                    </div>
                <?php
                endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);
            
            // Actualizar texto de ayuda según el tipo de ajuste
            var radioButtons = document.querySelectorAll('input[name="tipo_ajuste"]');
            var cantidadHelper = document.getElementById('cantidad-helper');
            var ajusteInfo = document.getElementById('ajuste-info');
            
            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'absoluto') {
                        cantidadHelper.textContent = 'Ingrese la nueva cantidad total de este producto en el almacén seleccionado';
                        ajusteInfo.innerHTML = '<strong>Nota:</strong> Este ajuste sobrescribirá la cantidad actual del producto en el almacén seleccionado.';
                    } else if (this.value === 'incremento') {
                        cantidadHelper.textContent = 'Ingrese la cantidad a añadir al stock actual';
                        ajusteInfo.innerHTML = '<strong>Nota:</strong> Esta cantidad se sumará al stock actual del producto en el almacén.';
                    } else if (this.value === 'decremento') {
                        cantidadHelper.textContent = 'Ingrese la cantidad a restar del stock actual';
                        ajusteInfo.innerHTML = '<strong>Nota:</strong> Esta cantidad se restará del stock actual del producto en el almacén.';
                    }
                });
            });
        });
    </script>
</body>
</html>
