<?php
// Mensaje de éxito o error
$mensaje = $_SESSION['mensaje'] ?? '';
if (isset($_SESSION['mensaje'])) {
    unset($_SESSION['mensaje']);
}
$errores = $_SESSION['errores'] ?? [];
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
    <title>Eliminar Categoría</title>
    <link rel="stylesheet" href="../../../public/css/index.css">
    <link rel="stylesheet" href="../../../public/css/categorias.css">
</head>
<body>
    <div class="container">
        <h2>Confirmar Eliminación de Categoría</h2>
        
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

        <?php if ($categoria): ?>
            <div class="card" style="margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                <h4>Confirmar Eliminación de Categoría</h4>
                
                <div style="margin: 20px 0;">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($categoria['nombre']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                </div>
                
                <?php if (!empty($error['en_uso'])): ?>
                    <div style="background: #ffebee; border: 1px solid #f44336; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>❌ No se puede eliminar:</strong> <?php echo htmlspecialchars($error['en_uso']); ?></p>
                    </div>
                    <div style="margin-top: 20px;">
                        <a href="../../Controller/productos/ListarCategoriasController.php" class="btn btn-secondary">
                            Volver al listado
                        </a>
                    </div>
                <?php else: ?>
                    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>⚠️ Advertencia:</strong> Esta acción no se puede deshacer. La categoría será marcada como eliminada y ya no aparecerá en los listados.</p>
                    </div>
                    
                    <form method="POST" action="" style="margin-top: 20px;">
                        <button type="submit" name="confirmar_eliminacion" class="btn btn-delete" style="margin-right: 10px;">
                            Sí, eliminar categoría
                        </button>
                        <a href="../../Controller/productos/ListarCategoriasController.php" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                No se encontró la categoría especificada.
            </div>
            <a href="../../Controller/productos/ListarCategoriasController.php" class="btn btn-secondary">
                Volver al listado
            </a>
        <?php endif; ?>
    </div>
</body>
</html> 