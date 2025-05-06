<?php
/**
 * Vista de Historial de Movimientos de Stock
 * 
 * Esta vista muestra un listado completo de todos los movimientos de inventario
 * con opciones de filtrado y paginación.
 */

// Valores predeterminados para la paginación
$pagina_actual = $pagina_actual ?? 1;
$registros_por_pagina = $registros_por_pagina ?? 10;
$total_registros = $total_registros ?? ($resultado ? $resultado->num_rows : 0);
$total_paginas = $total_paginas ?? ceil($total_registros / $registros_por_pagina);

// Construir parámetros de URL para mantener los filtros
$params_url = '';
if (isset($_GET['almacen']) && !empty($_GET['almacen'])) {
    $params_url .= '&almacen=' . urlencode($_GET['almacen']);
}
if (isset($_GET['producto']) && !empty($_GET['producto'])) {
    $params_url .= '&producto=' . urlencode($_GET['producto']);
}
if (isset($_GET['tipo']) && !empty($_GET['tipo'])) {
    $params_url .= '&tipo=' . urlencode($_GET['tipo']);
}
if (isset($_GET['fecha_desde']) && !empty($_GET['fecha_desde'])) {
    $params_url .= '&fecha_desde=' . urlencode($_GET['fecha_desde']);
}
if (isset($_GET['fecha_hasta']) && !empty($_GET['fecha_hasta'])) {
    $params_url .= '&fecha_hasta=' . urlencode($_GET['fecha_hasta']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .metrics-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .metric-card {
            flex: 1;
            margin: 0 10px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
        }
        .metric-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .metric-icon.entries { color: #4caf50; }
        .metric-icon.exits { color: #f44336; }
        .metric-icon.transfers { color: #2196f3; }
        .metric-icon.adjustments { color: #ff9800; }
        .metric-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .filter-section { margin-bottom: 20px; }
        .filter-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .input-group i { margin-right: 10px; }
        .date-range {
            display: flex;
            justify-content: space-between;
        }
        .date-range .input-group {
            flex: 1;
            margin-right: 10px;
        }
        .date-range .input-group:last-child { margin-right: 0; }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-info { font-size: 1rem; }
        .table-actions {
            display: flex;
            align-items: center;
        }
        .table-actions button { margin-left: 10px; }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .page-buttons {
            display: flex;
            align-items: center;
        }
        .page-btn {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        .page-btn.active {
            background-color: #2196f3;
            color: #fff;
        }
        .page-btn.disabled {
            color: #bdbdbd;
            cursor: not-allowed;
        }
        .empty-state {
            text-align: center;
            margin-top: 50px;
        }
        .empty-state i {
            font-size: 3rem;
            color: #bdbdbd;
            margin-bottom: 20px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: #fff;
        }
        .badge-success { background-color: #4caf50; }
        .badge-danger { background-color: #f44336; }
        .badge-info { background-color: #2196f3; }
        .badge-warning { background-color: #ff9800; }
        .quantity-positive { color: #4caf50; }
        .quantity-negative { color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-exchange-alt"></i> Historial de Movimientos</h4>
                        <p>Visualiza y analiza todos los movimientos de inventario</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn blue">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </div>
                </div>

                <!-- Summary Metrics -->
                <div class="row metrics-grid">
                    <div class="col s12 m4">
                        <div class="metric-card">
                            <div class="metric-icon entries">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">
                                    <?php
                                    $entradas = 0;
                                    if (isset($movimientosPorTipo) && isset($movimientosPorTipo['entrada'])) {
                                        $entradas = $movimientosPorTipo['entrada'];
                                    }
                                    echo $entradas;
                                    ?>
                                </div>
                                <div class="metric-label">Entradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <div class="metric-card">
                            <div class="metric-icon exits">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">
                                    <?php
                                    $salidas = 0;
                                    if (isset($movimientosPorTipo) && isset($movimientosPorTipo['salida'])) {
                                        $salidas = $movimientosPorTipo['salida'];
                                    }
                                    echo $salidas;
                                    ?>
                                </div>
                                <div class="metric-label">Salidas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <div class="metric-card">
                            <div class="metric-icon transfers">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="metric-details">
                                <div class="metric-value">
                                    <?php
                                    $transferencias = 0;
                                    if (isset($movimientosPorTipo) && isset($movimientosPorTipo['transferencia'])) {
                                        $transferencias = $movimientosPorTipo['transferencia'];
                                    }
                                    echo $transferencias;
                                    ?>
                                </div>
                                <div class="metric-label">Transferencias</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row filter-section">
                    <form method="GET" action="../../Controller/stock/movimientoStockController.php" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="fas fa-warehouse prefix"></i>
                                <input type="text" id="almacen" name="almacen" value="<?= isset($_GET['almacen']) ? htmlspecialchars($_GET['almacen']) : '' ?>">
                                <label for="almacen">Buscar por almacén</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-box prefix"></i>
                                <input type="text" id="producto" name="producto" value="<?= isset($_GET['producto']) ? htmlspecialchars($_GET['producto']) : '' ?>">
                                <label for="producto">Buscar por producto</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="fas fa-tags prefix"></i>
                                <select id="tipo" name="tipo" class="browser-default">
                                    <option value="" <?= !isset($_GET['tipo']) || $_GET['tipo'] === '' ? 'selected' : '' ?>>Todos los tipos</option>
                                    <option value="entrada" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                                    <option value="salida" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'salida' ? 'selected' : '' ?>>Salida</option>
                                    <option value="transferencia" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                                </select>
                                <label for="tipo" class="active">Tipo de Movimiento</label>
                            </div>
                        </div>
                        <!-- Eliminar el segundo selector de tipo que está duplicado -->
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="fas fa-calendar prefix"></i>
                                <input type="date" id="fecha_desde" name="fecha_desde" value="<?= isset($_GET['fecha_desde']) ? htmlspecialchars($_GET['fecha_desde']) : '' ?>">
                                <label for="fecha_desde" class="active">Desde</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="fas fa-calendar prefix"></i>
                                <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?= isset($_GET['fecha_hasta']) ? htmlspecialchars($_GET['fecha_hasta']) : '' ?>">
                                <label for="fecha_hasta" class="active">Hasta</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 right-align">
                                <button type="submit" class="btn blue">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <button type="button" class="btn grey" onclick="window.location='../../Controller/stock/movimientoStockController.php'">
                                    <i class="fas fa-sync-alt"></i> Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Controls -->
                <div class="row table-controls">
                    <div class="col s12 m6">
                        <p><strong><?= $resultado ? $resultado->num_rows : 0 ?></strong> resultados encontrados</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <button class="btn grey" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>

                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <!-- Table Results -->
                    <div class="table-responsive">
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Almacén Origen</th>
                                    <th>Almacén Destino</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while ($row = $resultado->fetch_assoc()):
                                    // Determine badge class based on movement type
                                    $badgeClass = "badge-warning";
                                    $movementType = strtolower($row["tipo_movimiento"]);

                                    if (strpos($movementType, "entrada") !== false) {
                                        $badgeClass = "badge-success";
                                    } elseif (strpos($movementType, "salida") !== false) {
                                        $badgeClass = "badge-danger";
                                    } elseif (strpos($movementType, "transferencia") !== false) {
                                        $badgeClass = "badge-info";
                                    }

                                    // Determine quantity class
                                    $quantityClass = "";
                                    $quantity = (int) $row["cantidad"];
                                    if ($quantity > 0) {
                                        $quantityClass = "quantity-positive";
                                    } elseif ($quantity < 0) {
                                        $quantityClass = "quantity-negative";
                                    }

                                    // Format date
                                    $date = new DateTime($row["fecha_movimiento"]);
                                    $formattedDate = $date->format("d/m/Y H:i");
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($row["producto"]) ?></strong></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($row["tipo_movimiento"]) ?></span></td>
                                    <td class="<?= $quantityClass ?>"><?= htmlspecialchars($row["cantidad"]) ?></td>
                                    <td><?= $formattedDate ?></td>
                                    <td><?= htmlspecialchars($row["almacen_origen"] ?: "-") ?></td>
                                    <td><?= htmlspecialchars($row["almacen_destino"] ?: "-") ?></td>
                                    <td><?= htmlspecialchars($row["usuario"]) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Reemplazar la sección de paginación estática por esta dinámica -->
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <!-- Pagination -->
                        <div class="pagination">
                            <div class="page-info">
                                <?php
                                $inicio = ($pagina_actual - 1) * $registros_por_pagina + 1;
                                $fin = min($inicio + $registros_por_pagina - 1, $total_registros);
                                ?>
                                Mostrando <?= $inicio ?>-<?= $fin ?> de <?= $total_registros ?> resultados
                            </div>
                            <div class="page-buttons">
                                <!-- Botón Anterior -->
                                <a href="?page=<?= max(1, $pagina_actual - 1) ?><?= $params_url ?>" class="page-btn <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                                
                                <!-- Primera página -->
                                <?php if ($pagina_actual > 3): ?>
                                    <a href="?page=1<?= $params_url ?>" class="page-btn">1</a>
                                    <?php if ($pagina_actual > 4): ?>
                                        <span>...</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <!-- Páginas cercanas a la actual -->
                                <?php
                                $inicio_paginas = max(1, $pagina_actual - 1);
                                $fin_paginas = min($total_paginas, $pagina_actual + 1);
                                
                                // Asegurar que siempre mostramos al menos 3 páginas si hay suficientes
                                if ($fin_paginas - $inicio_paginas < 2 && $total_paginas > 2) {
                                    if ($pagina_actual < $total_paginas / 2) {
                                        $fin_paginas = min($total_paginas, $inicio_paginas + 2);
                                    } else {
                                        $inicio_paginas = max(1, $fin_paginas - 2);
                                    }
                                }
                                
                                for ($i = $inicio_paginas; $i <= $fin_paginas; $i++):
                                ?>
                                    <a href="?page=<?= $i ?><?= $params_url ?>" class="page-btn <?= $i == $pagina_actual ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <!-- Última página -->
                                <?php if ($pagina_actual < $total_paginas - 2): ?>
                                    <?php if ($pagina_actual < $total_paginas - 3): ?>
                                        <span>...</span>
                                    <?php endif; ?>
                                    <a href="?page=<?= $total_paginas ?><?= $params_url ?>" class="page-btn"><?= $total_paginas ?></a>
                                <?php endif; ?>
                                
                                <!-- Botón Siguiente -->
                                <a href="?page=<?= min($total_paginas, $pagina_actual + 1) ?><?= $params_url ?>" class="page-btn <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron movimientos</h3>
                        <p>No hay registros de movimientos que coincidan con los criterios de búsqueda especificados. Intente con otros filtros o realice un nuevo movimiento.</p>
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn blue">
                            <i class="fas fa-plus-circle"></i> Nuevo Movimiento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicialización de componentes de Materialize
            M.AutoInit();
        });
    </script>
</body>
</html>