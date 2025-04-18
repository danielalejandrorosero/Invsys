<?php
// dbugar


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
nivelRequerido(1);

class ReporteController {
    private $stockModel;
    private $errores = [];

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }
    public function reporteStockController() {
        $almacen   = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
        $categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : null;
        $proveedor = isset($_GET['proveedor']) ? trim($_GET['proveedor']) : null;


        $resultado = $this->stockModel->generarReporte($almacen, $categoria,$proveedor);


        require_once __DIR__ . '/../../Views/stock/reporteStockVista.php';
    }



}


$reporteStock = new ReporteController($conn);
$reporteStock->reporteStockController();







?>