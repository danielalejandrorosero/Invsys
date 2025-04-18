<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .progress {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .required {
            color: red;
        }
        .error-message {
            color: #F44336;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        .error-message.visible {
            display: block;
        }
        .input-field.error input, 
        .input-field.error textarea,
        .input-field.error .select-wrapper input.select-dropdown {
            border-bottom: 1px solid #F44336 !important;
            box-shadow: 0 1px 0 0 #F44336 !important;
        }
        .input-field.error label {
            color: #F44336 !important;
        }
    </style>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Materialize components
            var selects = document.querySelectorAll('select');
            M.FormSelect.init(selects);
            
            var tabs = document.querySelectorAll('.tabs');
            var tabsInstance = M.Tabs.init(tabs[0]);
            
            // Form sections and navigation
            const sections = document.querySelectorAll('.form-section');
            const progressBar = document.querySelector('.progress-bar-fill');
            let currentTab = 0;
            
            // Section IDs in order
            const sectionIds = ['basic-info', 'details', 'prices', 'inventory'];
            
            // Update progress bar function
            function updateProgress() {
                const progressPercentage = ((currentTab + 1) / sections.length) * 100;
                progressBar.style.width = progressPercentage + '%';
            }
            
            // Validate section fields
            function validateSection(sectionIndex) {
                const section = sections[sectionIndex];
                const requiredFields = section.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;
                
                // Reset all error states
                section.querySelectorAll('.input-field').forEach(field => {
                    field.classList.remove('error');
                    const errorMsg = field.querySelector('.error-message');
                    if (errorMsg) errorMsg.classList.remove('visible');
                });
                
                // Check each required field
                requiredFields.forEach(field => {
                    let fieldValue = field.value.trim();
                    let isFieldValid = true;
                    
                    // Special handling for select elements
                    if (field.tagName === 'SELECT') {
                        const selectedIndex = field.selectedIndex;
                        if (selectedIndex === 0 || field.options[selectedIndex].value === '') {
                            isFieldValid = false;
                        }
                    } else {
                        // Regular input and textarea validation
                        if (fieldValue === '') {
                            isFieldValid = false;
                        }
                    }
                    
                    // Mark field as error if invalid
                    if (!isFieldValid) {
                        isValid = false;
                        const inputField = field.closest('.input-field');
                        if (inputField) {
                            inputField.classList.add('error');
                            const errorMsg = inputField.querySelector('.error-message');
                            if (errorMsg) errorMsg.classList.add('visible');
                        }
                    }
                });
                
                return isValid;
            }
            
            // Show specific section
            function showSection(index) {
                // If trying to advance, validate current section first
                if (index > currentTab) {
                    if (!validateSection(currentTab)) {
                        // Show toast notification
                        M.toast({
                            html: 'Por favor complete todos los campos obligatorios',
                            classes: 'red',
                            displayLength: 3000
                        });
                        return false;
                    }
                }
                
                // Hide all sections
                sections.forEach(section => {
                    section.classList.remove('active');
                });
                
                // Show the selected section
                sections[index].classList.add('active');
                
                // Update tab selection in Materialize
                tabsInstance.select(sectionIds[index]);
                
                // Update current tab index
                currentTab = index;
                
                // Update progress bar
                updateProgress();
                
                return true;
            }
            
            // Next button click handler
            const nextButtons = document.querySelectorAll('.btn-next');
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (currentTab < sections.length - 1) {
                        showSection(currentTab + 1);
                    }
                });
            });
            
            // Previous button click handler
            const prevButtons = document.querySelectorAll('.btn-prev');
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (currentTab > 0) {
                        showSection(currentTab - 1);
                    }
                });
            });
            
            // Tab click handler - sync with form sections
            tabs[0].addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    const href = e.target.getAttribute('href');
                    if (href) {
                        const sectionId = href.substring(1); // Remove the # character
                        const index = sectionIds.indexOf(sectionId);
                        if (index !== -1) {
                            // Only allow clicking on tabs if all previous sections are valid
                            let canProceed = true;
                            for (let i = 0; i < index; i++) {
                                if (!validateSection(i)) {
                                    canProceed = false;
                                    break;
                                }
                            }
                            
                            if (canProceed) {
                                showSection(index);
                            } else {
                                // Prevent tab change and show error
                                e.preventDefault();
                                e.stopPropagation();
                                
                                // Show toast notification
                                M.toast({
                                    html: 'Por favor complete todos los campos obligatorios en las secciones anteriores',
                                    classes: 'red',
                                    displayLength: 3000
                                });
                                
                                // Reset tab selection
                                setTimeout(() => {
                                    tabsInstance.select(sectionIds[currentTab]);
                                }, 10);
                            }
                        }
                    }
                }
            });
            
            // Form submission validation
            document.getElementById('producto-form').addEventListener('submit', function(e) {
                // Validate all sections before submitting
                for (let i = 0; i < sections.length; i++) {
                    if (!validateSection(i)) {
                        e.preventDefault();
                        showSection(i);
                        M.toast({
                            html: 'Por favor complete todos los campos obligatorios antes de enviar',
                            classes: 'red',
                            displayLength: 3000
                        });
                        return false;
                    }
                }
                return true;
            });
            
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
            
            // Initialize the first section
            showSection(0);
        });
    </script>
</body>
</html>