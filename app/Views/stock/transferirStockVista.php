<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferir Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/transferirStock.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle warehouse card selection
            const warehouseCards = document.querySelectorAll('.warehouse-card');
            const warehouseRadios = document.querySelectorAll('.warehouse-radio');

            warehouseCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Get the radio input inside this card
                    const radio = this.querySelector('.warehouse-radio');

                    // Check the radio
                    radio.checked = true;

                    // Remove selected class from all cards
                    warehouseCards.forEach(card => {
                        card.classList.remove('selected');
                    });

                    // Add selected class to clicked card
                    this.classList.add('selected');
                });
            });

            // Quantity input validation
            const quantityInput = document.getElementById('cantidad');
            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    const max = parseInt(this.getAttribute('max'));

                    if (value > max) {
                        this.value = max;
                    }

                    if (value < 1) {
                        this.value = 1;
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
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="header-text">
                        <h1>Transferir Stock</h1>
                        <p>Traslade productos entre almacenes de manera segura</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Step indicators -->
                <div class="steps-container">
                    <?php
                    $step1Class = "complete";
                    $step2Class = !isset($almacen_origen)
                        ? "active"
                        : "complete";
                    $step3Class =
                        isset($almacen_origen) &&
                        !isset($_POST["transferirStock"])
                            ? "active"
                            : "";
                    ?>
                    <div class="step <?php echo $step1Class; ?>">
                        <div class="step-circle"><i class="fas fa-box"></i></div>
                        <div class="step-label">Seleccionar Producto</div>
                    </div>
                    <div class="step <?php echo $step2Class; ?>">
                        <div class="step-circle"><i class="fas fa-warehouse"></i></div>
                        <div class="step-label">Seleccionar Almacenes</div>
                    </div>
                    <div class="step <?php echo $step3Class; ?>">
                        <div class="step-circle"><i class="fas fa-exchange-alt"></i></div>
                        <div class="step-label">Completar Transferencia</div>
                    </div>
                </div>

                <?php if (!isset($almacen_origen)): ?>
                    <!-- First step: Select product -->
                    <form action="../../Controller/stock/transferirStock.php" method="POST" class="step-form">
                        <div class="form-group">
                            <label for="id_producto" class="form-label">Seleccione el producto a transferir:</label>
                            <select id="id_producto" name="id_producto" class="form-control" onchange="this.form.submit()" required>
                                <option value="">Seleccione un producto</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto[
                                        "id_producto"
                                    ]; ?>" <?php if (
    isset($id_producto) &&
    $id_producto == $producto["id_producto"]
) {
    echo "selected";
} ?>>
                                        <?php echo $producto["nombre"]; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="obtenerAlmacenOrigen" value="1">
                    </form>
                <?php
                    // Get product name
                    // Skip the origin warehouse
                    // Get product name
                    // Skip the origin warehouse
                    else: ?>
                    <!-- Second/Third step: Complete transfer -->
                    <?php
                    $productoSeleccionado = null;
                    foreach ($productos as $producto) {
                        if ($producto["id_producto"] == $id_producto) {
                            $productoSeleccionado = $producto;
                            break;
                        }
                    }
                    ?>

                    <div class="selected-value">
                        <div class="selected-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="selected-details">
                            <h3><?php echo htmlspecialchars(
                                $productoSeleccionado["nombre"]
                            ); ?></h3>
                            <p>
                                <?php
                                $details = [];
                                if (!empty($productoSeleccionado["codigo"])) {
                                    $details[] =
                                        "Código: " .
                                        htmlspecialchars(
                                            $productoSeleccionado["codigo"]
                                        );
                                }
                                if (!empty($productoSeleccionado["sku"])) {
                                    $details[] =
                                        "SKU: " .
                                        htmlspecialchars(
                                            $productoSeleccionado["sku"]
                                        );
                                }
                                echo implode(" | ", $details);
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="selected-value">
                        <div class="selected-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="selected-details">
                            <h3>Almacén Origen: <?php echo htmlspecialchars(
                                $almacen_origen["nombre"]
                            ); ?></h3>
                            <p>
                                <?php echo "Stock disponible: <strong>" .
                                    htmlspecialchars(
                                        $almacen_origen[
                                            "cantidad_disponible"
                                        ] ?? "N/A"
                                    ) .
                                    "</strong> unidades"; ?>
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Seleccione un almacén destino y la cantidad a transferir.</strong>
                            Asegúrese de no exceder la cantidad disponible en el almacén origen.
                        </div>
                    </div>

                    <form action="../../Controller/stock/transferirStock.php" method="POST">
                        <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $id_producto; ?>">
                        <input type="hidden" id="id_almacen_origen" name="id_almacen_origen" value="<?php echo $almacen_origen[
                            "id_almacen"
                        ]; ?>">

                        <div class="form-group">
                            <label class="form-label">Seleccione el almacén destino:</label>
                            <div class="warehouse-grid">
                                <?php foreach (
                                    $almacenes
                                    as $index => $almacen
                                ):
                                    if (
                                        $almacen["id_almacen"] ==
                                        $almacen_origen["id_almacen"]
                                    ) {
                                        continue;
                                    } ?>
                                <label class="warehouse-card <?php echo $index ===
                                0
                                    ? "selected"
                                    : ""; ?>">
                                    <input type="radio" name="id_almacen_destino" value="<?php echo $almacen[
                                        "id_almacen"
                                    ]; ?>" class="warehouse-radio" <?php echo $index ===
0
    ? "checked"
    : ""; ?>>
                                    <div class="warehouse-icon">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                    <div class="warehouse-name"><?php echo htmlspecialchars(
                                        $almacen["nombre"]
                                    ); ?></div>
                                    <?php if (!empty($almacen["ubicacion"])): ?>
                                        <div class="warehouse-location"><?php echo htmlspecialchars(
                                            $almacen["ubicacion"]
                                        ); ?></div>
                                    <?php endif; ?>
                                </label>
                                <?php
                                endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cantidad" class="form-label">Cantidad a transferir:</label>
                            <input type="number" id="cantidad" name="cantidad" class="form-control quantity-input" min="1" max="<?php echo $almacen_origen[
                                "cantidad_disponible"
                            ] ?? 999; ?>" required>
                            <span class="quantity-hint">Máximo disponible: <?php echo $almacen_origen[
                                "cantidad_disponible"
                            ] ?? "N/A"; ?> unidades</span>
                        </div>

                        <div class="form-actions">
                            <a href="../../Controller/stock/transferirStock.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" name="transferirStock" class="btn btn-success">
                                <i class="fas fa-exchange-alt"></i> Completar Transferencia
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
