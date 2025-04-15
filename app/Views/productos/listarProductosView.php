<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 95%;
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        /* Mensajes de éxito y error */
        .mensaje-exito {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            border-left: 5px solid #28a745;
        }

        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            border-left: 5px solid #dc3545;
        }

        /* Botones de acción */
        .botones-accion {
            margin: 20px 0;
            display: flex;
            justify-content: flex-end;
        }

        .boton {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .boton-agregar {
            background-color: #28a745;
        }

        .boton-agregar:hover {
            background-color: #218838;
        }

        .boton-editar {
            background-color: #007bff;
        }

        .boton-editar:hover {
            background-color: #0069d9;
        }

        .boton-ver {
            background-color: #17a2b8;
        }

        .boton-ver:hover {
            background-color: #138496;
        }

        .boton-eliminar {
            background-color: #dc3545;
        }

        .boton-eliminar:hover {
            background-color: #c82333;
        }

        /* Tabla de productos */
        .tabla-productos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .tabla-productos th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #ddd;
        }

        .tabla-productos td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        .tabla-productos tbody tr:hover {
            background-color: #f5f5f5;
        }

        .tabla-productos tbody tr:last-child td {
            border-bottom: none;
        }

        .acciones {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .acciones a {
            padding: 6px 10px;
            font-size: 0.9em;
        }

        /* Estilos responsivos */
        @media (max-width: 1200px) {
            .tabla-productos {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 15px;
                margin: 10px auto;
            }

            .acciones {
                flex-direction: column;
            }

            .acciones a {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Productos</h1>

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
            <a href="../../Controller/productos/agregarProductoController.php" class="boton boton-agregar">Agregar Nuevo Producto</a>
            <a href="../../Controller/productos/ListarProductosEliminadosController.php" class="boton boton-ver">Ver Productos Eliminados</a>
        </div>

        <?php if (empty($productos)): ?>
            <p>No hay productos registrados.</p>
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
                        <th>Stock Mínimo</th>
                        <th>Stock Máximo</th>
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
                            <td><?php echo htmlspecialchars(
                                $producto["stock_minimo"]
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                $producto["stock_maximo"]
                            ); ?></td>
                            <td class="acciones">
                                <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto[
                                    "id_producto"
                                ]; ?>" class="boton boton-editar">Editar</a>
                                <a href="../../Controller/productos/VerProductoController.php?id=<?php echo $producto[
                                    "id_producto"
                                ]; ?>" class="boton boton-ver">Ver</a>
                                <a href="javascript:void(0);" onclick="confirmarEliminar(<?php echo $producto[
                                    "id_producto"
                                ]; ?>)" class="boton boton-eliminar">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function confirmarEliminar(idProducto) {
            if (confirm('¿Está seguro de que desea eliminar este producto?')) {
                window.location.href = '../../Controller/productos/eliminarProductoController.php?id=' + idProducto;
            }
        }
    </script>
</body>
</html>
