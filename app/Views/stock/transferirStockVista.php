<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferir Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f5f5f5;
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .header-content {
            display: flex;
            align-items: center;
        }
        .header-icon {
            font-size: 2rem;
            margin-right: 10px;
        }
        .header-text h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .header-text p {
            margin: 0;
            color: #757575;
        }
        .card-body {
            padding: 20px;
        }
        .steps-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .step {
            text-align: center;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .step.complete .step-circle {
            background-color: #4caf50;
            color: #fff;
        }
        .step.active .step-circle {
            background-color: #2196f3;
            color: #fff;
        }
        .step-label {
            font-size: 0.875rem;
            color: #757575;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .warehouse-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .warehouse-card {
            flex: 1 1 calc(50% - 10px);
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .warehouse-card.selected {
            background-color: #e0f7fa;
        }
        .warehouse-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .warehouse-name {
            font-weight: bold;
        }
        .warehouse-location {
            color: #757575;
        }
        .quantity-hint {
            display: block;
            margin-top: 5px;
            color: #757575;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-secondary {
            background-color: #e0e0e0;
            color: #424242;
        }
        .btn-secondary:hover {
            background-color: #d5d5d5;
        }
        .btn-success {
            background-color: #4caf50;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #43a047;
        }
        .alert {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #e0f7fa;
            border: 1px solid #b2ebf2;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert i {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .alert strong {
            font-weight: bold;
        }
    </style>
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
                <?php else: ?>
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
