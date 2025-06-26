<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Models/productos/productos.php';

nivelRequerido(1);

class ProductosSinAlmacenController {
    private $stockModel;
    private $productoModel;
    
    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        $this->productoModel = new Productos($conn);
    }

    public function mostrarProductosSinAlmacen() {
        $productosSinAlmacen = $this->stockModel->obtenerProductosSinAlmacen();
        $totalProductos = count($productosSinAlmacen);
        
        // Estadísticas adicionales
        $stats = [
            'total' => $totalProductos,
            'con_codigo' => 0,
            'con_sku' => 0,
            'con_categoria' => 0,
            'con_proveedor' => 0
        ];
        
        foreach ($productosSinAlmacen as $producto) {
            if (!empty($producto['codigo'])) $stats['con_codigo']++;
            if (!empty($producto['sku'])) $stats['con_sku']++;
            if (!empty($producto['categoria'])) $stats['con_categoria']++;
            if (!empty($producto['proveedor'])) $stats['con_proveedor']++;
        }
        
        require_once __DIR__ . '/../../Views/stock/productosSinAlmacenVista.php';
    }
}

$controller = new ProductosSinAlmacenController($conn);
$controller->mostrarProductosSinAlmacen();
?> 