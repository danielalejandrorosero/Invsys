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
    <link rel="stylesheet" href="../../../public/css/crearAlmacen.css">
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
</body>
</html>
