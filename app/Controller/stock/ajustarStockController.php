<?php

// dbugear
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
nivelRequerido(1);

class ControlInventarioController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }

    public function ajustarStock() {
        $error = [];
        $almacenes = [];
        $productos = $this->stockModel->obtenerProductos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajustar_stock'])) {
            $id_producto = (int) $_POST['id_producto'];
            $id_almacen = (int) $_POST['id_almacen'];
            $cantidad = (int) $_POST['cantidad'];

            if ($cantidad < 0) {
                $error[] = "La cantidad debe ser mayor o igual a 0.";
            }

            if (empty($error)) {
                $resultado = $this->stockModel->ajustarStock($id_producto, $id_almacen, $cantidad);
                if ($resultado) {
                    echo "Stock actualizado correctamente.";
                } else {
                    echo "Error al actualizar stock.";
                }
            } else {
                print_r($error);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seleccionar_producto'])) {
            $id_producto = (int) $_POST['id_producto'];
            $almacenes = $this->stockModel->obtenerAlmacenes();
        }

        require_once __DIR__ . '/../../Views/stock/ajustarStock.php';
    }
}

$controlInventario = new ControlInventarioController($conn);
$controlInventario->ajustarStock();
?>