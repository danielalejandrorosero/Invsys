<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/agregarProducto.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const tabs = document.querySelectorAll('.form-tab');
            const sections = document.querySelectorAll('.form-section');
            const progressBar = document.querySelector('.progress-bar-fill');
            let currentTab = 0;

            // Initialize with first tab active
            tabs[0].classList.add('active');
            sections[0].classList.add('active');
            updateProgress();

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs and sections
                    tabs.forEach(t => t.classList.remove('active'));
                    sections.forEach(s => s.classList.remove('active'));

                    // Add active class to clicked tab and corresponding section
                    tab.classList.add('active');
                    sections[index].classList.add('active');

                    // Update current tab index
                    currentTab = index;
                    updateProgress();
                });
            });

            // Next and Previous buttons
            const nextButtons = document.querySelectorAll('.btn-next');
            const prevButtons = document.querySelectorAll('.btn-prev');

            nextButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentTab < tabs.length - 1) {
                        tabs[currentTab].classList.remove('active');
                        sections[currentTab].classList.remove('active');
                        currentTab++;
                        tabs[currentTab].classList.add('active');
                        sections[currentTab].classList.add('active');
                        updateProgress();
                    }
                });
            });

            prevButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentTab > 0) {
                        tabs[currentTab].classList.remove('active');
                        sections[currentTab].classList.remove('active');
                        currentTab--;
                        tabs[currentTab].classList.add('active');
                        sections[currentTab].classList.add('active');
                        updateProgress();
                    }
                });
            });

            function updateProgress() {
                const progressPercentage = ((currentTab + 1) / tabs.length) * 100;
                progressBar.style.width = progressPercentage + '%';
            }

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
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h1>Agregar Producto</h1>
                        <p>Complete el formulario para agregar un nuevo producto al inventario</p>
                    </div>
                </div>
                <a href="../../Views/usuarios/index.php" class="back-btn">
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

                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-bar-fill"></div>
                </div>

                <!-- Tabs Navigation -->
                <div class="form-tabs">
                    <div class="form-tab active">
                        <i class="fas fa-info-circle"></i> Información Básica
                    </div>
                    <div class="form-tab">
                        <i class="fas fa-file-alt"></i> Detalles
                    </div>
                    <div class="form-tab">
                        <i class="fas fa-tags"></i> Precios
                    </div>
                    <div class="form-tab">
                        <i class="fas fa-boxes"></i> Inventario
                    </div>
                </div>

                <form action="../../Controller/productos/agregarProductoController.php" method="POST">
                    <!-- Section 1: Basic Information -->
                    <div class="form-section active">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre del Producto <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-box input-icon"></i>
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre del producto" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="codigo" class="form-label">Código <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-barcode input-icon"></i>
                                    <input type="text" id="codigo" name="codigo" class="form-control" placeholder="Código único del producto" required>
                                </div>
                                <div class="form-info">
                                    <i class="fas fa-info-circle"></i> El código debe ser único para cada producto
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sku" class="form-label">SKU <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-fingerprint input-icon"></i>
                                    <input type="text" id="sku" name="sku" class="form-control" placeholder="Stock Keeping Unit" required>
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
                                            ]; ?>">
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
                                <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Descripción detallada del producto" required></textarea>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div></div>
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Section 2: Details -->
                    <div class="form-section">
                        <div class="form-grid">
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
                                            ]; ?>">
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
                                            ]; ?>">
                                                <?php echo htmlspecialchars(
                                                    $unidad["nombre"]
                                                ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Section 3: Prices -->
                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="precio_compra" class="form-label">Precio de Compra <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-tag input-icon"></i>
                                    <input type="text" id="precio_compra" name="precio_compra" class="form-control price-input" placeholder="0.00" required>
                                    <span class="input-addon">$</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="precio_venta" class="form-label">Precio de Venta <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-dollar-sign input-icon"></i>
                                    <input type="text" id="precio_venta" name="precio_venta" class="form-control price-input" placeholder="0.00" required>
                                    <span class="input-addon">$</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary btn-next">
                                Siguiente <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Section 4: Inventory -->
                    <div class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="stock_minimo" class="form-label">Stock Mínimo <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-level-down-alt input-icon"></i>
                                    <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" placeholder="0" min="0" required>
                                </div>
                                <div class="form-info">
                                    <i class="fas fa-info-circle"></i> Nivel mínimo para recibir alertas de stock bajo
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="stock_maximo" class="form-label">Stock Máximo <span class="required">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-level-up-alt input-icon"></i>
                                    <input type="number" id="stock_maximo" name="stock_maximo" class="form-control" placeholder="0" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary btn-prev">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" name="agregarProducto" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Agregar Producto
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
