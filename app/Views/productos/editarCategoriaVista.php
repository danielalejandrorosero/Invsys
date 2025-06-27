<?php
// Mensaje de éxito o error
$mensaje = $_SESSION['mensaje'] ?? '';
if (isset($_SESSION['mensaje'])) {
    unset($_SESSION['mensaje']);
}
$errores = $error ?? ($_SESSION['errores'] ?? []);
if (!is_array($errores)) {
    $errores = empty($errores) ? [] : [$errores];
}
if (isset($_SESSION['errores'])) {
    unset($_SESSION['errores']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/categorias.css">
</head>
<body>
    <div class="container">
        <h2>Editar Categoría</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errores as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required minlength="3" maxlength="100" value="<?php echo htmlspecialchars($categoria['nombre'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" maxlength="500"><?php echo htmlspecialchars($categoria['descripcion'] ?? ''); ?></textarea>
            </div>
            <button type="submit" name="editarCategoria" class="btn btn-primary">Guardar Cambios</button>
            <a href="../../Controller/productos/ListarCategoriasController.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
