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
    
    // ===== CONFIGURACIÓN PARA EL LECTOR DE CÓDIGO DE BARRAS =====
    
    // Variable para almacenar el código escaneado
    let scanBuffer = '';
    let scanTimeout = null;
    const SCAN_TIMEOUT_MS = 50; // Tiempo máximo entre caracteres para considerar que es un escaneo
    
    // Definir patrón para el código de barras - acepta cualquier secuencia de dígitos
    const patronCodigo = /^\d+$/; // Acepta cualquier número de dígitos
    
    // Función para procesar el código escaneado
    function procesarCodigoEscaneado(codigo) {
        // Verificar si el código contiene solo dígitos
        if (patronCodigo.test(codigo)) {
            // Obtener el campo de código
            const campoCodigo = document.getElementById('codigo');
            
            if (campoCodigo) {
                // Asignar el código escaneado al campo
                campoCodigo.value = codigo;
                // Activar el evento change para que Materialize actualice las etiquetas
                campoCodigo.dispatchEvent(new Event('change'));
                
                // Mostrar notificación de éxito
                M.toast({
                    html: '¡Código escaneado correctamente!',
                    classes: 'green',
                    displayLength: 2000
                });
                
                // Resaltar visualmente el campo
                resaltarCampo(campoCodigo);
                
                // Mover el foco al siguiente campo (nombre del producto)
                setTimeout(() => {
                    document.getElementById('nombre').focus();
                }, 500);
            }
        } else {
            // Código no reconocido (contiene caracteres no numéricos)
            M.toast({
                html: 'El código debe contener solo números',
                classes: 'orange',
                displayLength: 2000
            });
        }
    }
    
    // Función para resaltar visualmente un campo
    function resaltarCampo(campo) {
        // Agregar clase de resaltado
        const inputField = campo.closest('.input-field');
        if (inputField) {
            inputField.classList.add('scanner-highlight');
            
            // Quitar la clase después de un tiempo
            setTimeout(() => {
                inputField.classList.remove('scanner-highlight');
            }, 1000);
        }
    }
    
    // Prevenir que el escáner escriba en el campo de nombre
    const nombreInput = document.getElementById('nombre');
    if (nombreInput) {
        nombreInput.addEventListener('input', function(e) {
            // Si detectamos un patrón que parece ser un código escaneado, limpiamos el campo
            const valor = this.value;
            if (patronCodigo.test(valor) && valor.length > 5) {
                this.value = ''; // Limpiar el campo
                M.toast({
                    html: 'Por favor, ingrese el nombre del producto manualmente',
                    classes: 'blue',
                    displayLength: 2000
                });
            }
        });
    }
    
    // Escuchar eventos de teclado a nivel de documento para capturar escaneos
    document.addEventListener('keydown', function(e) {
        // Si es Enter y hay algo en el buffer, procesar como código escaneado
        if (e.key === 'Enter' && scanBuffer.length > 0) {
            e.preventDefault(); // Prevenir envío de formulario
            
            const codigo = scanBuffer.trim();
            scanBuffer = ''; // Limpiar buffer
            
            procesarCodigoEscaneado(codigo);
            return;
        }
        
        // Si es un carácter imprimible, agregarlo al buffer
        if (e.key.length === 1 || e.key === '-' || e.key === '_') {
            // Reiniciar el timeout cada vez que se presiona una tecla
            if (scanTimeout) {
                clearTimeout(scanTimeout);
            }
            
            scanBuffer += e.key;
            
            // Configurar un timeout para detectar el fin del escaneo
            // (los escáneres suelen enviar caracteres muy rápidamente)
            scanTimeout = setTimeout(() => {
                // Si no se reciben más caracteres en el tiempo definido y no hay Enter,
                // asumimos que es una entrada manual y limpiamos el buffer
                scanBuffer = '';
            }, SCAN_TIMEOUT_MS);
        }
    });
    
    // Validación adicional para el campo de código
    const codigoInput = document.getElementById('codigo');
    if (codigoInput) {
        codigoInput.addEventListener('change', function() {
            if (patronCodigo.test(this.value)) {
                this.classList.add('valid');
                this.classList.remove('invalid');
            } else {
                this.classList.add('invalid');
                this.classList.remove('valid');
            }
        });
    }
});