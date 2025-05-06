<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Models/productos/productos.php';
nivelRequerido(1);

class ControlInventarioController {
    private $stockModel;
    private $productos;
    
    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        $this->productos = new Productos($conn);
    }

    public function verInventario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ver_inventario'])) {
            $id_almacen = (int) $_POST['id_almacen'];
            $inventario = $this->stockModel->verInventario($id_almacen);
            require_once __DIR__ . '/../../Views/stock/verInventario.php';
        }
    }

    public function obtenerListas() {
        $almacenes = $this->stockModel->obtenerAlmacenes();
        $inventarioGlobal = $this->stockModel->obtenerMovimientosRecientes();
        $productos = $this->productos->contarTotalProductos();
        $productosBajoStock = $this->stockModel->obtenerProductosBajoStock();
        require_once __DIR__ . '/../../Views/stock/formularioStock.php';
    }
}

$controlInventario = new ControlInventarioController($conn);
$controlInventario->verInventario();
$controlInventario->obtenerListas();
?>