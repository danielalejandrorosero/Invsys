<?php
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
    <link rel="stylesheet" href="../../../public/css/agregarProveedor.css">

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
    <script src="../../../public/js/agregarProveedor.js"></script>

</body>
</html>