<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';
require_once __DIR__ . '/../../Models/productos/productos.php';


class ControlInventarioController {
    
    private $stockModel;
    private $productoModel;
    
    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        $this->productoModel = new Productos($conn);
    }

    public function ajustarStock() {
        nivelRequerido(1);
        $error = [];
        $almacenes = [];
        $productos = $this->productoModel->obtenerProductos();
        $mensaje = '';
        $tipo_mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajustar_stock'])) {
            $id_producto = (int) $_POST['id_producto'];
            $id_almacen = (int) $_POST['id_almacen'];
            $cantidad = (int) $_POST['cantidad'];
            $tipo_ajuste = $_POST['tipo_ajuste'] ?? 'absoluto';

            // Validaciones
            if ($cantidad < 0) {
                $error[] = "La cantidad debe ser mayor o igual a 0.";
            }
            
            if ($tipo_ajuste === 'decremento' && $cantidad <= 0) {
                $error[] = "Para disminuir stock, la cantidad debe ser mayor que 0.";
            }
            
            if ($tipo_ajuste === 'incremento' && $cantidad <= 0) {
                $error[] = "Para incrementar stock, la cantidad debe ser mayor que 0.";
            }

            if (empty($error)) {
                $resultado = false;
                
                switch ($tipo_ajuste) {
                    case 'absoluto':
                        $resultado = $this->stockModel->ajustarStock($id_producto, $id_almacen, $cantidad);
                        $accion = "establecido en";
                        break;
                        
                    case 'incremento':
                        $resultado = $this->stockModel->incrementarStock($id_producto, $id_almacen, $cantidad);
                        $accion = "incrementado en";
                        break;
                        
                    case 'decremento':
                        $resultado = $this->stockModel->disminuirStock($id_producto, $id_almacen, $cantidad);
                        $accion = "disminuido en";
                        break;
                        
                    default:
                        $error[] = "Tipo de ajuste no válido.";
                        break;
                }
                
                if ($resultado) {
                    $mensaje = "Stock {$accion} {$cantidad} unidades correctamente.";
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = "Error al ajustar stock. Verifique que hay suficiente stock disponible.";
                    $tipo_mensaje = 'error';
                }
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