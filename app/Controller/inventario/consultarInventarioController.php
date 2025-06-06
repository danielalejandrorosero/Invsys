<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/productos/productos.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class ConsultarInventarioController {
    private $productosModel;
    private $stockModel;

    public function __construct($conn) {
        $this->productosModel = new Productos($conn);
        $this->stockModel = new Stock($conn);
    }

    public function consultarInventario() {
        nivelRequerido(1);
        
        $respuesta = [];
        
        // Obtener total de productos
        $totalProductos = $this->productosModel->contarTotalProductos();
        $respuesta['total_productos'] = $totalProductos;
        
        // Obtener productos con stock bajo
        $productosBajoStock = $this->stockModel->obtenerProductosBajoStock();
        $respuesta['productos_bajo_stock'] = count($productosBajoStock);
        
        // Obtener movimientos por tipo
        $movimientosPorTipo = $this->stockModel->contarMovimientosPorTipo();
        $respuesta['movimientos'] = $movimientosPorTipo;
        
        // Obtener transferencias pendientes
        $transferenciasPendientes = $this->stockModel->contarTransferenciasPendientes();
        $respuesta['transferencias_pendientes'] = $transferenciasPendientes;
        
        // Devolver respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    }
}

// Inicializar el controlador y procesar la consulta
$controller = new ConsultarInventarioController($conn);
$controller->consultarInventario(); 