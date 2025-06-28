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
    <link rel="stylesheet" href="../../../public/css/editarProveedor.css">

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
                    <a href="../../Controller/proveedores/listarProveedores.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../../public/js/editarProveedor.js"></script>
</body>
</html>