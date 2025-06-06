<?php
/**
 * Vista de Historial de Proveedores
 * 
 * Esta vista muestra la información detallada de un proveedor y sus productos asociados
 * con opciones de filtrado, ordenamiento y paginación.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Proveedor - InvSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-history"></i> Historial del Proveedor</h4>
                        <p>Información detallada y productos asociados</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <a href="../../Controller/proveedores/listarProveedores.php" class="btn grey">
                            <i class="material-icons left">arrow_back</i> Volver
                        </a>
                    </div>
                </div>

                <!-- Información del Proveedor -->
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel blue lighten-4">
                            <h5>Información del Proveedor</h5>
                            <div class="row">
                                <div class="col s12 m6">
                                    <p><strong>Nombre:</strong> <?= htmlspecialchars($historial['proveedor']['nombre']) ?></p>
                                    <p><strong>Contacto:</strong> <?= htmlspecialchars($historial['proveedor']['contacto']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($historial['proveedor']['email']) ?></p>
                                </div>
                                <div class="col s12 m6">
                                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($historial['proveedor']['telefono']) ?></p>
                                    <p><strong>Dirección:</strong> <?= htmlspecialchars($historial['proveedor']['direccion']) ?></p>
                                    <p><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($historial['proveedor']['estado'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros y Ordenamiento -->
                <div class="row">
                    <div class="col s12">
                        <form method="GET" class="card-panel">
                            <input type="hidden" name="id" value="<?= $id_proveedor ?>">
                            <div class="row">
                                <div class="col s12 m4">
                                    <label>Ordenar por:</label>
                                    <select name="orden" onchange="this.form.submit()">
                                        <option value="nombre" <?= $orden === 'nombre' ? 'selected' : '' ?>>Nombre</option>
                                        <option value="codigo" <?= $orden === 'codigo' ? 'selected' : '' ?>>Código</option>
                                        <option value="sku" <?= $orden === 'sku' ? 'selected' : '' ?>>SKU</option>
                                        <option value="precio_venta" <?= $orden === 'precio_venta' ? 'selected' : '' ?>>Precio</option>
                                    </select>
                                </div>
                                <div class="col s12 m4">
                                    <label>Estado:</label>
                                    <select name="estado" onchange="this.form.submit()">
                                        <option value="activo" <?= $filtro_estado === 'activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="inactivo" <?= $filtro_estado === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                        <option value="eliminado" <?= $filtro_estado === 'eliminado' ? 'selected' : '' ?>>Eliminado</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Productos -->
                <div class="row">
                    <div class="col s12">
                        <table class="striped responsive-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>SKU</th>
                                    <th>Descripción</th>
                                    <th>Precio Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($historial['productos'])): ?>
                                    <tr>
                                        <td colspan="5" class="center-align">No hay productos asociados</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($historial['productos'] as $producto): ?>
                                        <tr>
                                            <td>
                                                <div class="product-name">
                                                    <i class="fas fa-box"></i>
                                                    <?= htmlspecialchars($producto['nombre']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-code">
                                                    <i class="fas fa-barcode"></i>
                                                    <?= htmlspecialchars($producto['codigo']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-sku">
                                                    <i class="fas fa-tag"></i>
                                                    <?= htmlspecialchars($producto['sku']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-description">
                                                    <i class="fas fa-info-circle"></i>
                                                    <?= htmlspecialchars($producto['descripcion']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-price">
                                                    <i class="fas fa-dollar-sign"></i>
                                                    $<?= number_format($producto['precio_venta'], 2) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación -->
                <?php if ($total_paginas > 1): ?>
                <div class="row">
                    <div class="col s12 center-align">
                        <ul class="pagination">
                            <?php if ($pagina_actual > 1): ?>
                                <li class="waves-effect">
                                    <a href="../../Controller/proveedores/historialProveedoresController.php?id=<?= $id_proveedor ?>&page=<?= $pagina_actual - 1 ?><?= $params_url ?>">
                                        <i class="material-icons">chevron_left</i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="<?= $i === $pagina_actual ? 'active' : 'waves-effect' ?>">
                                    <a href="../../Controller/proveedores/historialProveedoresController.php?id=<?= $id_proveedor ?>&page=<?= $i ?><?= $params_url ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagina_actual < $total_paginas): ?>
                                <li class="waves-effect">
                                    <a href="../../Controller/proveedores/historialProveedoresController.php?id=<?= $id_proveedor ?>&page=<?= $pagina_actual + 1 ?><?= $params_url ?>">
                                        <i class="material-icons">chevron_right</i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar select de Materialize
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);
        });
    </script>
</body>
</html> 