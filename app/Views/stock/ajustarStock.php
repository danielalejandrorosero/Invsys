<?php
$error = $error ?? [];
$productos = $productos ?? [];
$almacenes = $almacenes ?? [];
$mensaje = $mensaje ?? '';
$tipo_mensaje = $tipo_mensaje ?? '';
$id_producto_selected = $_POST['id_producto'] ?? $id_producto_selected ?? '';
$id_almacen_selected = $_POST['id_almacen'] ?? $id_almacen_selected ?? '';
$cantidad_selected = $_POST['cantidad'] ?? $cantidad_selected ?? '';
$tipo_ajuste_selected = $_POST['tipo_ajuste'] ?? $tipo_ajuste_selected ?? 'absoluto';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustar Stock - Sistema de Inventario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/ajustarStock.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-boxes me-2"></i>
                            Ajustar Stock de Productos
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <!-- Mostrar mensajes -->
                        <?php if (isset($mensaje) && !empty($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipo_mensaje === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                <?php echo htmlspecialchars($mensaje); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Mostrar errores -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Error:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($error as $err): ?>
                                        <li><?php echo htmlspecialchars($err); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario de selección de producto -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-box-open me-2"></i>
                                Paso 1: Seleccionar Producto
                            </h5>
                            
                            <form method="POST" class="mb-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="id_producto" class="form-label">
                                            <i class="fas fa-box me-1"></i>
                                            Seleccionar Producto
                                        </label>
                                        <select name="id_producto" id="id_producto" class="form-select" required>
                                            <option value="">-- Seleccione un producto --</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?php echo $producto['id_producto']; ?>" 
                                                        <?php echo ($id_producto_selected == $producto['id_producto']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">Seleccione el producto cuyo inventario desea ajustar</div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" name="seleccionar_producto" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-search me-1"></i>
                                            Seleccionar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php if (!empty($almacenes) && isset($id_producto_selected) && !empty($id_producto_selected)):
                            // Find the selected product information
                            $productoSeleccionado = null;
                            foreach ($productos as $producto) {
                                if ($producto['id_producto'] == $id_producto_selected) {
                                    $productoSeleccionado = $producto;
                                    break;
                                }
                            }
                            ?>
                            
                            <!-- Product info if available -->
                            <?php if ($productoSeleccionado): ?>
                                <div class="product-info d-flex align-items-center">
                                    <div class="product-icon me-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="product-details">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($productoSeleccionado['nombre']); ?></h5>
                                        <p class="mb-0 text-muted">
                                            <?php if (isset($productoSeleccionado['codigo'])): ?>
                                                Código: <?php echo htmlspecialchars($productoSeleccionado['codigo']); ?>
                                            <?php endif; ?>
                                            <?php if (isset($productoSeleccionado['sku'])): ?>
                                                | SKU: <?php echo htmlspecialchars($productoSeleccionado['sku']); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Stock actual en todos los almacenes -->
                                <?php if (isset($stock_actual) && !empty($stock_actual)): ?>
                                    <div class="alert alert-info mt-3" role="alert">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Stock Actual del Producto
                                        </h6>
                                        <div class="row mt-2">
                                            <?php foreach ($stock_actual as $stock): ?>
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold"><?php echo htmlspecialchars($stock['nombre_almacen']); ?>:</span>
                                                        <span class="badge bg-primary"><?php echo $stock['cantidad_disponible']; ?> unidades</span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Se ha pre-seleccionado el almacén con mayor stock disponible.
                                        </small>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Formulario de ajuste de stock -->
                            <hr class="my-4">
                            <h5 class="mb-3">
                                <i class="fas fa-warehouse me-2"></i>
                                Paso 2: Ajustar Cantidad
                            </h5>
                            
                            <form method="POST">
                                <input type="hidden" name="id_producto" value="<?php echo $id_producto_selected; ?>">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="id_almacen" class="form-label">
                                            <i class="fas fa-warehouse me-1"></i>
                                            Almacén
                                        </label>
                                        <select name="id_almacen" id="id_almacen" class="form-select" required>
                                            <option value="">-- Seleccione un almacén --</option>
                                            <?php foreach ($almacenes as $almacen): ?>
                                                <option value="<?php echo $almacen['id_almacen']; ?>"
                                                        <?php echo ($id_almacen_selected == $almacen['id_almacen']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($almacen['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">Seleccione el almacén donde desea ajustar el inventario</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="cantidad" class="form-label">
                                            <i class="fas fa-calculator me-1"></i>
                                            Cantidad
                                        </label>
                                        <input type="number" 
                                               name="cantidad" 
                                               id="cantidad" 
                                               class="form-control" 
                                               min="0" 
                                               step="1" 
                                               value="<?php echo htmlspecialchars($cantidad_selected); ?>"
                                               required>
                                        <div class="form-text" id="cantidad-helper">Ingrese la nueva cantidad total de este producto en el almacén seleccionado</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-cogs me-1"></i>
                                        Tipo de Ajuste
                                    </label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo_ajuste" id="absoluto" value="absoluto" <?php echo ($tipo_ajuste_selected === 'absoluto') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="absoluto">
                                                    Establecer cantidad absoluta
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo_ajuste" id="incremento" value="incremento" <?php echo ($tipo_ajuste_selected === 'incremento') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="incremento">
                                                    Incrementar stock (sumar)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo_ajuste" id="decremento" value="decremento" <?php echo ($tipo_ajuste_selected === 'decremento') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="decremento">
                                                    Disminuir stock (restar)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <div id="ajuste-info">
                                        <strong>Nota:</strong> Este ajuste sobrescribirá la cantidad actual del producto en el almacén seleccionado.
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <a href="../../Controller/stock/verInventarioController.php" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Volver al Inventario
                                    </a>
                                    <div>
                                        <button type="button" onclick="history.back()" class="btn btn-outline-secondary me-2">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </button>
                                        <button type="submit" name="ajustar_stock" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Ajustar Stock
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/ajustarStock.js"></script>
</body>
</html>