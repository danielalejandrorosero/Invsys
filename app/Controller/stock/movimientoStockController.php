<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class MovimientoStockController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }
    
    public function mostrarMovimientos() {
        // parámetros de paginación
        $limit = 10 ; // Número de productos por página
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        
        $almacen = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
        $producto = isset($_GET['producto']) ? trim($_GET['producto']) : null;
        $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : null;
        $fecha_desde = isset($_GET['fecha_desde']) ? trim($_GET['fecha_desde']) : null;
        $fecha_hasta = isset($_GET['fecha_hasta']) ? trim($_GET['fecha_hasta']) : null;
        
        // Obtener todos los movimientos para contar el total (sin límite)
        $todosMovimientos = $this->stockModel->obtenerMovimientos(
            $almacen, 
            $producto, 
            $tipo,
            $fecha_desde,
            $fecha_hasta
        );
        
        // Guardar el total de registros
        $total_registros = $todosMovimientos->num_rows;
        
        // Obtener los movimientos paginados (con límite y offset)
        $resultado = $this->stockModel->obtenerMovimientos(
            $almacen, 
            $producto, 
            $tipo,
            $fecha_desde,
            $fecha_hasta,
            $limit,
            $offset
        );
        
        // Calcular el número total de páginas
        $totalPaginas = ceil($total_registros / $limit);
        
        // Obtener contadores por tipo para las métricas
        $movimientosPorTipo = $this->stockModel->contarMovimientosPorTipo();
        
        // Variables para la vista
        $pagina_actual = $page;
        $registros_por_pagina = $limit;
        $total_paginas = $totalPaginas;
        
        // Construir parámetros de URL para mantener los filtros
        $params_url = '';
        if ($almacen) $params_url .= '&almacen=' . urlencode($almacen);
        if ($producto) $params_url .= '&producto=' . urlencode($producto);
        if ($tipo) $params_url .= '&tipo=' . urlencode($tipo);
        if ($fecha_desde) $params_url .= '&fecha_desde=' . urlencode($fecha_desde);
        if ($fecha_hasta) $params_url .= '&fecha_hasta=' . urlencode($fecha_hasta);
        
        // Cargar la vista
        require_once __DIR__ . '/../../Views/stock/movimientoStockVista.php';
    }
}

// Inicializar el controlador y mostrar los movimientos
$movimientoStock = new MovimientoStockController($conn);
$movimientoStock->mostrarMovimientos();
?>