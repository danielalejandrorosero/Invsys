<?php
// Configuración de paginación
$pagina_actual = max(1, $pagina_actual ?? 1);
$registros_por_pagina = $registros_por_pagina ?? 10;
$total_registros = max(0, $total_registros ?? 0);
$total_paginas = max(1, $total_paginas ?? 1);

// Ajustar página actual si excede el total
if ($pagina_actual > $total_paginas && $total_paginas > 0) {
    $pagina_actual = $total_paginas;
}

// Construir parámetros de URL para mantener los filtros
$params_url = '';
$filtros = ['almacen', 'producto', 'tipo', 'fecha_desde', 'fecha_hasta'];
foreach ($filtros as $filtro) {
    if (isset($_GET[$filtro]) && !empty($_GET[$filtro])) {
        $params_url .= '&' . $filtro . '=' . urlencode($_GET[$filtro]);
    }
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
    <link rel="stylesheet" href="../../../public/css/historialMovimientos.css">
    <link rel="stylesheet" href="../../../public/css/exportarMovimientos.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-exchange-alt"></i>
                        Historial de Movimientos
                    </h1>
                    <div class="header-actions">
                        <div class="dropdown">
                            <button class="btn blue waves-effect waves-light dropdown-trigger" data-target="exportDropdown">
                                <i class="fas fa-file-export"></i>
                                Exportar
                                <i class="fas fa-chevron-down" style="margin-left: 8px;"></i>
                            </button>
                            <ul id="exportDropdown" class="dropdown-content">
                                <li><a href="../../Controller/stock/exportarMovimientosController.php?formato=csv<?= $params_url ?>" class="export-link">
                                    <i class="fas fa-file-csv"></i> Exportar CSV
                                </a></li>
                                <li><a href="../../Controller/stock/exportarMovimientosController.php?formato=excel<?= $params_url ?>" class="export-link">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </a></li>
                                <li><a href="../../Controller/stock/exportarMovimientosController.php?formato=pdf<?= $params_url ?>" class="export-link">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a></li>
                            </ul>
                        </div>
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey waves-effect waves-light">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                </div>

                <!-- Summary Metrics -->
                <div class="metrics-grid">
                    <div class="metric-card entries">
                        <div class="metric-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
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
                    
                    <div class="metric-card exits">
                        <div class="metric-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
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
                    
                    <div class="metric-card transfers">
                        <div class="metric-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
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
                    
                    <div class="metric-card adjustments">
                        <div class="metric-icon">
                            <i class="fas fa-sync"></i>
                        </div>
                        <div class="metric-value">
                            <?php
                            $ajustes = 0;
                            if (isset($movimientosPorTipo) && isset($movimientosPorTipo['ajuste'])) {
                                $ajustes = $movimientosPorTipo['ajuste'];
                            }
                            echo $ajustes;
                            ?>
                        </div>
                        <div class="metric-label">Ajustes</div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <h5 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Filtros de Búsqueda
                    </h5>
                    <form method="GET" action="../../Controller/stock/movimientoStockController.php">
                        <div class="filter-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div>
                                <label for="almacen" style="font-weight: 500; color: #2c3e50;">Buscar por almacén</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e9e9e; font-size: 1.2rem;"><i class="fas fa-warehouse"></i></span>
                                    <input type="text" id="almacen" name="almacen" style="padding-left: 38px;" value="<?= isset($_GET['almacen']) ? htmlspecialchars($_GET['almacen']) : '' ?>">
                                </div>
                            </div>
                            <div>
                                <label for="producto" style="font-weight: 500; color: #2c3e50;">Buscar por producto</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e9e9e; font-size: 1.2rem;"><i class="fas fa-box"></i></span>
                                    <input type="text" id="producto" name="producto" style="padding-left: 38px;" value="<?= isset($_GET['producto']) ? htmlspecialchars($_GET['producto']) : '' ?>">
                                </div>
                            </div>
                            <div>
                                <label for="fecha_desde" style="font-weight: 500; color: #2c3e50;">Fecha desde</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e9e9e; font-size: 1.2rem;"><i class="fas fa-calendar"></i></span>
                                    <input type="date" id="fecha_desde" name="fecha_desde" style="padding-left: 38px;" value="<?= isset($_GET['fecha_desde']) ? htmlspecialchars($_GET['fecha_desde']) : '' ?>">
                                </div>
                            </div>
                            <div>
                                <label for="fecha_hasta" style="font-weight: 500; color: #2c3e50;">Fecha hasta</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e9e9e; font-size: 1.2rem;"><i class="fas fa-calendar"></i></span>
                                    <input type="date" id="fecha_hasta" name="fecha_hasta" style="padding-left: 38px;" value="<?= isset($_GET['fecha_hasta']) ? htmlspecialchars($_GET['fecha_hasta']) : '' ?>">
                                </div>
                            </div>
                            <div style="grid-column: 1 / span 2;">
                                <label for="tipo" style="font-weight: 500; color: #2c3e50;">Tipo de Movimiento</label>
                                <select id="tipo" name="tipo" class="browser-default" style="width: 100%; margin-top: 4px;">
                                    <option value="" <?= !isset($_GET['tipo']) || $_GET['tipo'] === '' ? 'selected' : '' ?>>Todos los tipos</option>
                                    <option value="entrada" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                                    <option value="salida" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'salida' ? 'selected' : '' ?>>Salida</option>
                                    <option value="transferencia" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                                    <option value="ajuste" <?= isset($_GET['tipo']) && $_GET['tipo'] === 'ajuste' ? 'selected' : '' ?>>Ajuste</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions" style="margin-top: 24px;">
                            <button type="submit" class="btn blue waves-effect waves-light">
                                <i class="fas fa-search"></i>
                                Buscar
                            </button>
                            <button type="button" class="btn grey waves-effect waves-light" 
                                    onclick="window.location='../../Controller/stock/movimientoStockController.php'">
                                <i class="fas fa-sync-alt"></i>
                                Limpiar filtros
                            </button>
                        </div>
                    </form>
                </div>

                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <!-- Table Container -->
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-info">
                                <strong><?= $resultado->num_rows ?></strong> resultados encontrados
                            </div>
                            <div class="table-actions">
                                <button class="btn grey waves-effect waves-light" onclick="location.reload()">
                                    <i class="fas fa-sync-alt"></i>
                                    Actualizar
                                </button>
                            </div>
                        </div>

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
                                        // Determinar clase del badge según el tipo de movimiento
                                        $badgeClass = "badge-warning";
                                        $movementType = strtolower($row["tipo_movimiento"]);

                                        if (strpos($movementType, "entrada") !== false) {
                                            $badgeClass = "badge-success";
                                        } elseif (strpos($movementType, "salida") !== false) {
                                            $badgeClass = "badge-danger";
                                        } elseif (strpos($movementType, "transferencia") !== false) {
                                            $badgeClass = "badge-info";
                                        }

                                        // Determinar clase de cantidad
                                        $quantityClass = "";
                                        $quantity = (int) $row["cantidad"];
                                        if ($quantity > 0) {
                                            $quantityClass = "quantity-positive";
                                        } elseif ($quantity < 0) {
                                            $quantityClass = "quantity-negative";
                                        }

                                        // Formatear fecha
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
                                <a href="?page=<?= max(1, $pagina_actual - 1) ?><?= $params_url ?>" 
                                   class="page-btn <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                                
                                <!-- Primera página -->
                                <?php if ($pagina_actual > 3): ?>
                                    <a href="?page=1<?= $params_url ?>" class="page-btn">1</a>
                                    <?php if ($pagina_actual > 4): ?>
                                        <span style="padding: 8px 12px;">...</span>
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
                                    <a href="?page=<?= $i ?><?= $params_url ?>" 
                                       class="page-btn <?= $i == $pagina_actual ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <!-- Última página -->
                                <?php if ($pagina_actual < $total_paginas - 2): ?>
                                    <?php if ($pagina_actual < $total_paginas - 3): ?>
                                        <span style="padding: 8px 12px;">...</span>
                                    <?php endif; ?>
                                    <a href="?page=<?= $total_paginas ?><?= $params_url ?>" class="page-btn"><?= $total_paginas ?></a>
                                <?php endif; ?>
                                
                                <!-- Botón Siguiente -->
                                <a href="?page=<?= min($total_paginas, $pagina_actual + 1) ?><?= $params_url ?>" 
                                   class="page-btn <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron movimientos</h3>
                        <p>No hay registros de movimientos que coincidan con los criterios de búsqueda especificados. Intente con otros filtros o realice un nuevo movimiento.</p>
                        <a href="../../Controller/stock/ajustarStockController.php" class="btn blue waves-effect waves-light">
                            <i class="fas fa-plus-circle"></i>
                            Nuevo Movimiento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../../../public/js/exportarMovimiento.js"></script>
</body>
</html> 