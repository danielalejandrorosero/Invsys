<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Eliminados</title>
    <link rel="stylesheet" href="../../../frontend/styles.css">
</head>
<body>
    <div class="container">
        <h1>Productos Eliminados</h1>

        <?php if (isset($_SESSION["mensaje"])): ?>
            <div class="mensaje-exito">
                <?php echo $_SESSION["mensaje"]; ?>
                <?php unset($_SESSION["mensaje"]); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION["errores"])): ?>
            <div class="mensaje-error">
                <?php foreach ($_SESSION["errores"] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION["errores"]); ?>
            </div>
        <?php endif; ?>

        <div class="botones-accion">
            <a href="../../Controller/productos/ListarProductosController.php" class="boton boton-ver">Volver a Productos Activos</a>
        </div>

        <?php if (empty($productos)): ?>
            <p>No hay productos eliminados.</p>
        <?php else: ?>
            <table class="tabla-productos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>SKU</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(
                                $producto["id_producto"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["nombre"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["codigo"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["sku"]
                            ); ?></td>
                            <td>$<?php echo number_format(
                                $producto["precio_compra"],
                                2
                            ); ?></td>
                            <td>$<?php echo number_format(
                                $producto["precio_venta"],
                                2
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["categoria_nombre"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["proveedor_nombre"]
                            ); ?></td>
                            <td class="acciones">
                                <a href="../../Controller/productos/RestaurarProductoController.php?id=<?php echo $producto[
                                    "id_producto"
                                ]; ?>" class="boton boton-editar">Restaurar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
