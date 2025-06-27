<?php
// Mensaje de éxito
$mensaje = $_SESSION['mensaje'] ?? '';
if (isset($_SESSION['mensaje'])) {
    unset($_SESSION['mensaje']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Categoría</title>
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/agregarCategoria.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Nueva Categoría</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($error as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required minlength="3" maxlength="100" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" maxlength="500"><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
            </div>
            <button type="submit" name="agregar_categoria" class="btn btn-primary">Agregar Categoría</button>
            <a href="../../Controller/productos/ListarCategoriasController.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html> 