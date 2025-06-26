<?php
// Asegúrate de que las variables de sesión y errores estén disponibles.
// Por ejemplo, $error es pasado desde AgregarProveedorController.
// Si $error no está definido, inicialízalo como un array vacío para evitar errores.
if (!isset($error)) {
    $error = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Proveedor</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2em;
            font-weight: 300;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 0.95em;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            background-color: #fafbfc;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input.error {
            border-color: #e74c3c;
            background-color: #fdf2f2;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.85em;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .error-message::before {
            content: "⚠";
            font-size: 1.1em;
        }
        
        .success-message {
            color: #27ae60;
            background-color: #d5f4e6;
            border: 1px solid #27ae60;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .success-message::before {
            content: "✓";
            font-size: 1.2em;
            font-weight: bold;
        }
        
        .error-general {
            color: #e74c3c;
            background-color: #fdf2f2;
            border: 1px solid #e74c3c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .error-general::before {
            content: "❌";
            font-size: 1.1em;
        }
        
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 200px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .nav-links {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
        }
        
        .nav-links a {
            display: inline-block;
            margin: 0 15px 10px 15px;
            color: #667eea;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background-color: #667eea;
            color: white;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }
            
            h1 {
                font-size: 1.5em;
            }
            
            .nav-links a {
                display: block;
                margin: 5px 0;
            }
        }
        
        /* Animaciones para los campos con error */
        .form-group input.error {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Nuevo Proveedor</h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="success-message">
                <?php 
                    echo htmlspecialchars($_SESSION['mensaje']); 
                    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error['general'])): ?>
            <div class="error-general">
                <?php echo htmlspecialchars($error['general']); ?>
            </div>
        <?php endif; ?>

        <form action="../../Controller/proveedores/agregarProveedor.php" method="POST" novalidate>
            <div class="form-group">
                <label for="nombre">
                    <span>Nombre del Proveedor</span>
                    <span style="color: #e74c3c;">*</span>
                </label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" 
                    required
                    <?php echo !empty($error['nombre']) ? 'class="error"' : ''; ?>
                    placeholder="Ingrese el nombre del proveedor"
                >
                <?php if (!empty($error['nombre'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error['nombre']); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="contacto">
                    <span>Contacto</span>
                    <span style="color: #e74c3c;">*</span>
                </label>
                <input 
                    type="text" 
                    id="contacto" 
                    name="contacto" 
                    value="<?php echo isset($_POST['contacto']) ? htmlspecialchars($_POST['contacto']) : ''; ?>" 
                    required
                    <?php echo !empty($error['contacto']) ? 'class="error"' : ''; ?>
                    placeholder="Nombre de la persona de contacto"
                >
                <?php if (!empty($error['contacto'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error['contacto']); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="direccion">
                    <span>Dirección</span>
                    <span style="color: #e74c3c;">*</span>
                </label>
                <input 
                    type="text" 
                    id="direccion" 
                    name="direccion" 
                    value="<?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?>" 
                    required
                    <?php echo !empty($error['direccion']) ? 'class="error"' : ''; ?>
                    placeholder="Dirección completa del proveedor"
                >
                <?php if (!empty($error['direccion'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error['direccion']); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="telefono">
                    <span>Teléfono</span>
                    <span style="color: #e74c3c;">*</span>
                </label>
                <input 
                    type="text" 
                    id="telefono" 
                    name="telefono" 
                    value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" 
                    required
                    <?php echo !empty($error['telefono']) ? 'class="error"' : ''; ?>
                    placeholder="Número de teléfono"
                >
                <?php if (!empty($error['telefono'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error['telefono']); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">
                    <span>Email</span>
                    <span style="color: #e74c3c;">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                    required
                    <?php echo !empty($error['email']) ? 'class="error"' : ''; ?>
                    placeholder="correo@ejemplo.com"
                >
                <?php if (!empty($error['email'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error['email']); ?></div>
                <?php endif; ?>
            </div>

            <div class="btn-container">
                <button type="submit" name="agregar_proveedor" class="btn-primary">
                    Agregar Proveedor
                </button>
            </div>
        </form>

        <div class="nav-links">
            <a href="../../Controller/proveedores/listarProveedores.php">📋 Ver Lista de Proveedores</a>
            <a href="../../Views/usuarios/dashboard.php">🏠 Volver al Inicio</a>
        </div>
    </div>

    <script>
        // Validación en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
            
            function validateField(field) {
                const errorDiv = field.parentNode.querySelector('.error-message');
                
                if (field.value.trim() === '') {
                    field.classList.add('error');
                    return false;
                } else if (field.type === 'email' && !isValidEmail(field.value)) {
                    field.classList.add('error');
                    return false;
                } else {
                    field.classList.remove('error');
                    return true;
                }
            }
            
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
            
            // Validación antes de enviar
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll al primer error
                    const firstError = form.querySelector('.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
        });
    </script>
</body>
</html>