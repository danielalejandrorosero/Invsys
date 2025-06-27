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
    <title>Listado de Categorías</title>
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/categorias.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Categorías</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        <a href="../../Controller/productos/agregarCategoriaController.php" class="btn btn-primary">Agregar Nueva Categoría</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categorias) && is_array($categorias)): ?>
                    <?php foreach ($categorias as $i => $cat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cat['descripcion']); ?></td>
                            <td>
                                <a href="../../Controller/productos/editarCategoriaController.php?id=<?php echo urlencode($cat['id_categoria']); ?>" class="btn btn-edit">Editar</a>
                                <a href="../../Controller/productos/eliminarCategoriaController.php?id=<?php echo urlencode($cat['id_categoria']); ?>" class="btn btn-delete">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No hay categorías registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../../Views/usuarios/dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
    </div>
</body>
</html> 