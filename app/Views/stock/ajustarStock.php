<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustar Inventario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/ajustarStock.css">
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
            <div class="card-header">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="header-content">
                    <h1>Ajustar Inventario</h1>
                    <p>Actualizar la cantidad de productos en stock</p>
                </div>
            </div>

            <div class="card-body">
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
                        <div class="form-group">
                            <label for="id_producto" class="form-label">Producto:</label>
                            <div class="input-group">
                                <i class="fas fa-search input-icon"></i>
                                <select name="id_producto" id="id_producto" class="form-control" required>
                                    <option value="">Seleccione un producto</option>
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?php echo $producto[
                                            "id_producto"
                                        ]; ?>"
                                            <?php echo isset($id_producto) &&
                                            $id_producto ==
                                                $producto["id_producto"]
                                                ? "selected"
                                                : ""; ?>>
                                            <?php echo htmlspecialchars(
                                                $producto["nombre"]
                                            ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-hint">Seleccione el producto cuyo inventario desea ajustar</div>
                        </div>

                        <div class="form-actions">
                            <a href="../../Controller/stock/verInventarioController.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Inventario
                            </a>
                            <button type="submit" name="seleccionar_producto" class="btn btn-primary">
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
                            <input type="hidden" name="id_producto" value="<?php echo isset(
                                $id_producto
                            )
                                ? $id_producto
                                : ""; ?>">

                            <div class="form-group">
                                <label for="id_almacen" class="form-label">Almacén:</label>
                                <div class="input-group">
                                    <i class="fas fa-building input-icon"></i>
                                    <select name="id_almacen" id="id_almacen" class="form-control" required>
                                        <option value="">Seleccione un almacén</option>
                                        <?php foreach (
                                            $almacenes
                                            as $almacen
                                        ): ?>
                                            <option value="<?php echo $almacen[
                                                "id_almacen"
                                            ]; ?>">
                                                <?php echo htmlspecialchars(
                                                    $almacen["nombre"]
                                                ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-hint">Seleccione el almacén donde desea ajustar el inventario</div>
                            </div>

                            <div class="form-group">
                                <label for="cantidad" class="form-label">Nueva cantidad:</label>
                                <div class="input-group">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="number" id="cantidad" name="cantidad" class="form-control" min="0" required>
                                    <span class="input-suffix">unidades</span>
                                </div>
                                <div class="form-hint">Ingrese la nueva cantidad total de este producto en el almacén seleccionado</div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <strong>Nota:</strong> Este ajuste sobrescribirá la cantidad actual del producto en el almacén seleccionado.
                                    Para aumentar o disminuir el stock de forma relativa, utilice la sección de movimientos.
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" onclick="history.back()" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <button type="submit" name="ajustar_stock" class="btn btn-success">
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
</body>
</html>
