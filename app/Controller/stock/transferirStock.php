<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Models/productos/productos.php';

// Verificar nivel de acceso antes de crear el controlador
nivelRequerido(1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

class StockController {
    private $stockModel;
    private $productoModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        $this->productoModel = new Productos($conn);
    }

    public function transferirStock() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transferirStock'])) {
                $this->procesarTransferencia();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obtenerAlmacenOrigen'])) {
                $this->mostrarAlmacenOrigen();
            } else {
                $this->mostrarVistaInicial();
            }
        } catch (Exception $e) {
            $_SESSION['errores'][] = "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
            $this->mostrarVistaInicial();
        }
    }

    private function procesarTransferencia() {
        $error = [];

        // Validar datos de entrada
        $id_producto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
        $id_almacen_destino = isset($_POST['id_almacen_destino']) ? (int) $_POST['id_almacen_destino'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int) $_POST['cantidad'] : 0;
        $id_usuario = $_SESSION['id_usuario'];

        // Obtener automáticamente el almacén de origen
        $almacen_origen = $this->stockModel->obtenerAlmacenOrigen($id_producto);
        $id_almacen_origen = $almacen_origen ? $almacen_origen['id_almacen'] : 0;

        // Validaciones lógicas
        if ($id_producto <= 0 || !$this->productoModel->validarProducto($id_producto)) {
            $error[] = "Error: El producto seleccionado no es válido.";
        }

        if ($id_almacen_origen <= 0) {
            $error[] = "Error: No hay stock disponible del producto en ningún almacén.";
        }

        if ($id_almacen_destino <= 0 || !$this->stockModel->almacenExiste($id_almacen_destino)) {
            $error[] = "Error: El almacén de destino no es válido.";
        }

        if ($id_almacen_origen === $id_almacen_destino) {
            $error[] = "Error: El almacén de origen y destino no pueden ser el mismo.";
        }

        if ($cantidad <= 0) {
            $error[] = "Error: La cantidad de productos a transferir debe ser mayor a 0.";
        }

        if (empty($error)) {
            // Intentar realizar la transferencia
            $resultado = $this->stockModel->transferirStock($id_producto, $id_almacen_origen, $id_almacen_destino, $cantidad, $id_usuario);
            if ($resultado === true) {
                $_SESSION['mensaje'] = "Transferencia realizada con éxito.";
            } else {
                $_SESSION['errores'][] = $resultado;
            }
        } else {
            $_SESSION['errores'] = $error;
        }

        $this->mostrarVistaInicial();
    }

    private function mostrarAlmacenOrigen() {
        $id_producto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;

        if ($id_producto <= 0 || !$this->productoModel->validarProducto($id_producto)) {
            echo json_encode(['error' => 'Producto no válido']);
            exit();
        }
        
        // Obtener información del almacén de origen con stock disponible
        $stock_info = $this->stockModel->obtenerStockProducto($id_producto);
        
        if (empty($stock_info)) {
            echo json_encode(['error' => 'No hay stock disponible para este producto']);
            exit();
        }
        
        // Tomar el primer almacén (el que tiene más stock)
        $almacen_origen = $stock_info[0];
        
        echo json_encode([
            'id_almacen' => $almacen_origen['id_almacen'],
            'nombre' => $almacen_origen['nombre_almacen'],
            'cantidad_disponible' => $almacen_origen['cantidad_disponible']
        ]);
        exit();
    }

    private function mostrarVistaInicial() {
        $productos = $this->productoModel->obtenerProductos();
        $almacenes = $this->stockModel->obtenerAlmacenes();
        require_once __DIR__ . '/../../Views/stock/transferirStockVista.php';
    }
}

$stockController = new StockController($conn);
$stockController->transferirStock();