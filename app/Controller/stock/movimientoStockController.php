<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';


class MovimientoStockController {

    private $stockModel;


    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }


    public function mostrarMovimientos() {
        $almacen = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
        $producto = isset($_GET['producto']) ? trim($_GET['producto']) : null;
        $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : null;


        $resultados = $this->stockModel->obtenerMovimientos($almacen, $producto, $tipo);

        require_once __DIR__ . '/../../Views/stock/movimientoStockVista.php';

    }
}


$movimientoStock = new MovimientoStockController($conn);
$movimientoStock->mostrarMovimientos();


?>