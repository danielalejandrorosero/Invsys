<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';




class AlertaStockController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }


    public function alertaStock() {
        nivelRequerido(1); // Solo administradores pueden ver la alerta de stock


        // llamar al modelo
        $productosBajoStock = $this->stockModel->obtenerProductosBajoStock();

        return $productosBajoStock;
        //         require_once __DIR__ . '/../../Views/stock/alertaStock.php';

    }
}
?>