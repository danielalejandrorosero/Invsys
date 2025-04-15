<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Models/productos/productos.php';
nivelRequerido(1);



class StockController {
    private $stockModel;
    private $productoModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        $this->productoModel = new Productos($conn);
    }   

    public function transferirStock() {
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transferirStock'])) {
            $id_producto = (int) $_POST['id_producto'];
            $id_almacen_origen = (int) $_POST['id_almacen_origen'];
            $id_almacen_destino = (int) $_POST['id_almacen_destino'];
            $cantidad = (int) $_POST['cantidad'];
            // el id_usuario va a ser el id del usuario actual
            $id_usuario = $_SESSION['id_usuario'];

            if ($id_almacen_origen == $id_almacen_destino) {
                $error[] = "Error: El almacén de origen y destino no pueden ser el mismo.";
            }

            if ($cantidad <= 0) {
                $error[] = "Error: La cantidad de productos a transferir debe ser mayor a 0.";
            }

            if (empty($error)) {
                $resultado = $this->stockModel->transferirStock($id_producto, $id_almacen_origen, $id_almacen_destino, $cantidad, $id_usuario);
                if ($resultado === true) {
                    echo "<h2>Transferencia realizada con éxito</h2>";
                } else {
                    echo "<h2>Error en la transferencia</h2>";
                    echo "<p>" . htmlspecialchars($resultado) . "</p>";
                }
            } else {
                echo "<h2>Errores encontrados</h2>";
                foreach ($error as $err) {
                    echo "<p>" . htmlspecialchars($err) . "</p>";
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obtenerAlmacenOrigen'])) {
            $id_producto = (int) $_POST['id_producto'];
            $almacen_origen = $this->stockModel->obtenerAlmacenOrigen($id_producto);
            $productos = $this->productoModel->obtenerProductos();
            $almacenes = $this->stockModel->obtenerAlmacenes();
            require_once __DIR__ . '/../../Views/stock/transferirStockVista.php';
        } else {
            $productos = $this->productoModel->obtenerProductos();
            $almacenes = $this->stockModel->obtenerAlmacenes();
            require_once __DIR__ . '/../../Views/stock/transferirStockVista.php';
        }
    }
}

$stockController = new StockController($conn);
$stockController->transferirStock();
?>