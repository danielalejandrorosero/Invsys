<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Almacenes</title>
    <link rel="stylesheet" href="../../../public/css/listarAlmacenes.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-warehouse"></i> Almacenes</h2>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])): ?>
            <div class="alert danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?php foreach ($_SESSION['errores'] as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>
        <div class="actions">
            <a href="../../Controller/stock/crearAlmacen.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Almacén</a>
            <a href="../../Controller/stock/verInventarioController.php" class="btn btn-secondary"><i class="fas fa-boxes"></i> Inventario</a>
        </div>
        <?php if (!empty($almacenes)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($almacenes as $almacen): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($almacen['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($almacen['ubicacion']); ?></td>
                            <td>
                                <a href="../../Controller/stock/editarAlmacen.php?id=<?php echo $almacen['id_almacen']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
                                <a href="../../Controller/stock/eliminarAlmacenController.php?id=<?php echo $almacen['id_almacen']; ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este almacén?');"><i class="fas fa-trash-alt"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="info-box">
                <i class="fas fa-info-circle"></i> No hay almacenes registrados.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>