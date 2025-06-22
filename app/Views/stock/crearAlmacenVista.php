<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Almacén | InvSys</title>
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../../../public/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1><i class="fas fa-warehouse"></i> Crear Nuevo Almacén</h1>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['mensaje'];
                    unset($_SESSION['mensaje']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error['general']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="form">
                <div class="form-group">
                    <label for="nombre">Nombre del Almacén:</label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                           class="<?php echo isset($error['nombre']) ? 'error' : ''; ?>"
                           required>
                    <?php if (isset($error['nombre'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($error['nombre']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="ubicacion">Ubicación:</label>
                    <input type="text" 
                           id="ubicacion" 
                           name="ubicacion" 
                           value="<?php echo htmlspecialchars($_POST['ubicacion'] ?? ''); ?>"
                           class="<?php echo isset($error['ubicacion']) ? 'error' : ''; ?>"
                           required>
                    <?php if (isset($error['ubicacion'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($error['ubicacion']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" name="crear_almacen" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Almacén
                    </button>
                    <a href="../../Controller/stock/ListarAlmacenesController.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #34495e;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input.error {
            border-color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
            }

            .form-container {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</body>
</html>
