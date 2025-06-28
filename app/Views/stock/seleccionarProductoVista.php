<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferir Stock | Seleccionar Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/seleccionarProducto.css">
    <script src="../../../public/js/seleccionarProducto.js"></script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-exchange-alt"></i> Transferir Stock</h4>
                        <p>Mueva productos entre almacenes</p>
                    </div>
                </div>

                <!-- Step indicators -->
                <div class="steps-container">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-label">Seleccionar Producto</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">2</div>
                        <div class="step-label">Origen y Destino</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Cantidad</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">4</div>
                        <div class="step-label">Confirmar</div>
                    </div>
                </div>

                <?php if (!empty($_SESSION["error"])): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <div><?php
                        echo $_SESSION["error"];
                        unset($_SESSION["error"]);
                        ?></div>
                    </div>
                <?php endif; ?>

                <form action="../../Controller/stock/transferirStock.php" method="post">
                    <!-- Enhanced product selection interface -->
                    <div class="input-field">
                        <i class="fas fa-search prefix"></i>
                        <input type="text" id="searchProduct" placeholder="Buscar producto por nombre o código...">
                        <label for="searchProduct">Buscar y Seleccionar Producto</label>
                    </div>

                    <!-- Hidden select for form submission -->
                    <select id="id_producto" name="id_producto" style="display: none;" required>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= htmlspecialchars(
                                $producto["id_producto"]
                            ) ?>"><?= htmlspecialchars(
    $producto["nombre"]
) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Visual product selection -->
                    <div class="product-select">
                        <?php foreach ($productos as $index => $producto): ?>
                            <div class="product-option <?= $index === 0
                                ? "selected"
                                : "" ?>" data-id="<?= htmlspecialchars(
    $producto["id_producto"]
) ?>">
                                <div class="product-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="product-details">
                                    <div class="product-name"><?= htmlspecialchars(
                                        $producto["nombre"]
                                    ) ?></div>
                                    <div class="product-info">
                                        <?php
                                        $details = [];
                                        if (!empty($producto["codigo"])) {
                                            $details[] =
                                                "Código: " .
                                                htmlspecialchars(
                                                    $producto["codigo"]
                                                );
                                        }
                                        if (!empty($producto["sku"])) {
                                            $details[] =
                                                "SKU: " .
                                                htmlspecialchars(
                                                    $producto["sku"]
                                                );
                                        }
                                        if (!empty($producto["stock_actual"])) {
                                            $details[] =
                                                "Stock: " .
                                                htmlspecialchars(
                                                    $producto["stock_actual"]
                                                );
                                        }
                                        echo implode(" | ", $details);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($productos)): ?>
                            <div class="product-option" style="justify-content: center; color: var(--secondary);">
                                No hay productos disponibles
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn blue">
                            Continuar <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
