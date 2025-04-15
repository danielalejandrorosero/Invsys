<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferir Stock | Seleccionar Producto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/seleccionarProducto.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter products based on search input
            const searchInput = document.getElementById('searchProduct');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const productOptions = document.querySelectorAll('.product-option');

                    productOptions.forEach(option => {
                        const productName = option.querySelector('.product-name').textContent.toLowerCase();
                        const productInfo = option.querySelector('.product-info').textContent.toLowerCase();

                        if (productName.includes(searchTerm) || productInfo.includes(searchTerm)) {
                            option.style.display = 'flex';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
            }

            // Handle product selection
            const productOptions = document.querySelectorAll('.product-option');
            const productSelect = document.getElementById('id_producto');

            productOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    productOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Update hidden select value
                    const productId = this.getAttribute('data-id');
                    productSelect.value = productId;
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="header-content">
                        <h1>Transferir Stock</h1>
                        <p>Mueva productos entre almacenes</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
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
                    <div class="form-group">
                        <label for="searchProduct" class="form-label">Buscar y Seleccionar Producto</label>
                        <div class="search-product">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchProduct" placeholder="Buscar producto por nombre o código...">
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
                            <?php foreach (
                                $productos
                                as $index => $producto
                            ): ?>
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
                                            if (
                                                !empty(
                                                    $producto["stock_actual"]
                                                )
                                            ) {
                                                $details[] =
                                                    "Stock: " .
                                                    htmlspecialchars(
                                                        $producto[
                                                            "stock_actual"
                                                        ]
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
                    </div>

                    <div class="form-actions">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Continuar <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
