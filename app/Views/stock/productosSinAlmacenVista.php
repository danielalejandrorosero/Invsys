<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Sin Almacén | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stats-card {
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .product-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-exclamation-triangle orange-text"></i> Productos Sin Almacén</h4>
                        <p>Gestión de productos que no están asignados a ningún almacén o tienen stock cero</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <a href="../../Controller/stock/verInventarioController.php" class="btn blue">
                            <i class="fas fa-warehouse"></i> Ver Inventario
                        </a>
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row">
                    <div class="col s12 m3">
                        <div class="stats-card orange lighten-4 center">
                            <div class="stat-number orange-text"><?php echo $stats['total']; ?></div>
                            <div class="stat-label">Total Productos</div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="stats-card blue lighten-4 center">
                            <div class="stat-number blue-text"><?php echo $stats['con_codigo']; ?></div>
                            <div class="stat-label">Con Código</div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="stats-card green lighten-4 center">
                            <div class="stat-number green-text"><?php echo $stats['con_categoria']; ?></div>
                            <div class="stat-label">Con Categoría</div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="stats-card purple lighten-4 center">
                            <div class="stat-number purple-text"><?php echo $stats['con_proveedor']; ?></div>
                            <div class="stat-label">Con Proveedor</div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($productosSinAlmacen)): ?>
                    <!-- Tabla de Productos -->
                    <div class="table-responsive">
                        <table class="striped highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Categoría</th>
                                    <th>Proveedor</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productosSinAlmacen as $producto): ?>
                                    <tr class="orange lighten-5">
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <div class="product-avatar orange">
                                                    <?php echo strtoupper(mb_substr($producto["nombre"], 0, 1, "UTF-8")); ?>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($producto["nombre"]); ?></strong>
                                                    <?php if (!empty($producto["descripcion"])): ?>
                                                        <br><small class="grey-text"><?php echo htmlspecialchars(substr($producto["descripcion"], 0, 50)) . (strlen($producto["descripcion"]) > 50 ? '...' : ''); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($producto["codigo"])): ?>
                                                <span class="chip blue lighten-4 blue-text"><?php echo htmlspecialchars($producto["codigo"]); ?></span>
                                            <?php else: ?>
                                                <span class="chip grey lighten-4 grey-text">Sin código</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($producto["sku"])): ?>
                                                <span class="chip green lighten-4 green-text"><?php echo htmlspecialchars($producto["sku"]); ?></span>
                                            <?php else: ?>
                                                <span class="chip grey lighten-4 grey-text">Sin SKU</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($producto["categoria"])): ?>
                                                <span class="chip purple lighten-4 purple-text"><?php echo htmlspecialchars($producto["categoria"]); ?></span>
                                            <?php else: ?>
                                                <span class="chip grey lighten-4 grey-text">Sin categoría</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($producto["proveedor"])): ?>
                                                <span class="chip orange lighten-4 orange-text"><?php echo htmlspecialchars($producto["proveedor"]); ?></span>
                                            <?php else: ?>
                                                <span class="chip grey lighten-4 grey-text">Sin proveedor</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="new badge red" data-badge-caption="">Sin Stock</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="../../Controller/stock/ajustarStockController.php?id_producto=<?php echo $producto["id_producto"]; ?>" 
                                                   class="btn-floating btn-small waves-effect waves-light blue" 
                                                   title="Agregar Stock">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <a href="../../Controller/productos/editarProductoController.php?id=<?php echo $producto["id_producto"]; ?>" 
                                                   class="btn-floating btn-small waves-effect waves-light orange" 
                                                   title="Editar Producto">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/productos/verProductoController.php?id=<?php echo $producto["id_producto"]; ?>" 
                                                   class="btn-floating btn-small waves-effect waves-light green" 
                                                   title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Recomendaciones -->
                    <div class="card-panel orange lighten-4 orange-text text-darken-4">
                        <h6><i class="fas fa-lightbulb"></i> Recomendaciones de Gestión</h6>
                        <div class="row">
                            <div class="col s12 m6">
                                <h6>Productos que necesitan atención:</h6>
                                <ul class="browser-default">
                                    <li><strong>Sin código:</strong> <?php echo ($stats['total'] - $stats['con_codigo']); ?> productos</li>
                                    <li><strong>Sin categoría:</strong> <?php echo ($stats['total'] - $stats['con_categoria']); ?> productos</li>
                                    <li><strong>Sin proveedor:</strong> <?php echo ($stats['total'] - $stats['con_proveedor']); ?> productos</li>
                                </ul>
                            </div>
                            <div class="col s12 m6">
                                <h6>Acciones recomendadas:</h6>
                                <ul class="browser-default">
                                    <li>Completar información faltante de productos</li>
                                    <li>Agregar stock inicial a productos activos</li>
                                    <li>Eliminar productos descontinuados</li>
                                    <li>Revisar configuración de categorías y proveedores</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Estado Vacío -->
                    <div class="center-align" style="margin: 50px 0;">
                        <i class="fas fa-check-circle fa-3x green-text"></i>
                        <h5>¡Excelente! No hay productos sin almacén</h5>
                        <p>Todos los productos están correctamente asignados a almacenes con stock disponible.</p>
                        <a href="../../Controller/stock/verInventarioController.php" class="btn blue">
                            <i class="fas fa-warehouse"></i> Ver Inventario Completo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html> 