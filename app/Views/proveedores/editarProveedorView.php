<?php
// Verificar que exista la variable $proveedor
if (!isset($proveedor) || !$proveedor) {
    header('Location: ../../Controller/proveedores/ListarProveedoresController.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .page-header {
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .page-header p {
            font-size: 16px;
            opacity: 0.8;
            font-weight: 300;
        }

        .form-container {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 16px;
            background: #ffffff;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            background: #f8fafb;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.15);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .input-error {
            border-color: #e74c3c !important;
            background: #fdf2f2 !important;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 20%, 40%, 60%, 80% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        }

        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .error::before {
            content: "⚠";
            margin-right: 6px;
        }

        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex: 1;
            min-width: 150px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(149, 165, 166, 0.3);
        }

        .general-error {
            background: #e74c3c;
            color: white;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            text-align: center;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                margin: 10px auto;
            }

            .page-header {
                padding: 30px 20px;
            }

            .form-container {
                padding: 30px 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        /* Animación de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-group {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>Editar Proveedor</h2>
            <p>Modifica los datos del proveedor</p>
        </div>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre">Nombre del Proveedor *</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        value="<?php echo htmlspecialchars($proveedor['nombre'] ?? ''); ?>"
                        class="<?php echo isset($error['nombre']) ? 'input-error' : ''; ?>"
                        required
                    >
                    <?php if (isset($error['nombre'])): ?>
                        <div class="error"><?php echo $error['nombre']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="contacto">Persona de Contacto *</label>
                    <input 
                        type="text" 
                        id="contacto" 
                        name="contacto" 
                        value="<?php echo htmlspecialchars($proveedor['contacto'] ?? ''); ?>"
                        class="<?php echo isset($error['contacto']) ? 'input-error' : ''; ?>"
                        required
                    >
                    <?php if (isset($error['contacto'])): ?>
                        <div class="error"><?php echo $error['contacto']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección *</label>
                    <textarea 
                        id="direccion" 
                        name="direccion" 
                        class="<?php echo isset($error['direccion']) ? 'input-error' : ''; ?>"
                        required
                    ><?php echo htmlspecialchars($proveedor['direccion'] ?? ''); ?></textarea>
                    <?php if (isset($error['direccion'])): ?>
                        <div class="error"><?php echo $error['direccion']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono *</label>
                    <input 
                        type="text" 
                        id="telefono" 
                        name="telefono" 
                        value="<?php echo htmlspecialchars($proveedor['telefono'] ?? ''); ?>"
                        class="<?php echo isset($error['telefono']) ? 'input-error' : ''; ?>"
                        required
                    >
                    <?php if (isset($error['telefono'])): ?>
                        <div class="error"><?php echo $error['telefono']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($proveedor['email'] ?? ''); ?>"
                        class="<?php echo isset($error['email']) ? 'input-error' : ''; ?>"
                        required
                    >
                    <?php if (isset($error['email'])): ?>
                        <div class="error"><?php echo $error['email']; ?></div>
                    <?php endif; ?>
                </div>

                <?php if (isset($error['general'])): ?>
                    <div class="general-error">
                        <?php echo $error['general']; ?>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" name="editar_proveedor" class="btn btn-primary">
                        Actualizar Proveedor
                    </button>
                    <a href="../../Controller/proveedores/ListarProveedoresController.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validación del lado del cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = ['nombre', 'contacto', 'direccion', 'telefono', 'email'];
            
            requiredFields.forEach(function(fieldName) {
                const field = document.getElementById(fieldName);
                const value = field.value.trim();
                
                if (!value) {
                    field.classList.add('input-error');
                    valid = false;
                } else {
                    field.classList.remove('input-error');
                }
            });
            
            // Validación específica del email
            const emailField = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (emailField.value && !emailPattern.test(emailField.value)) {
                emailField.classList.add('input-error');
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor, complete todos los campos requeridos correctamente.');
            }
        });

        // Remover clase de error cuando el usuario empiece a escribir
        document.querySelectorAll('input, textarea').forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('input-error');
            });
        });

        // Efectos visuales adicionales
        document.querySelectorAll('input, textarea').forEach(function(field) {
            field.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            field.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>