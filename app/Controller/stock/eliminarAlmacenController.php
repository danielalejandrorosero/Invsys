<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class EliminarAlmacenController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }

    public function eliminarAlmacen() {
        nivelRequerido(1);
        
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['errores'] = ['ID de almacén no válido'];
            header('Location: ../../Controller/stock/ListarAlmacenesController.php');
            exit();
        }

        $id_almacen = (int) $_GET['id'];
        
        try {
            // Verificar si el almacén tiene productos
            if ($this->stockModel->almacenTieneProductos($id_almacen)) {
                $_SESSION['errores'] = ['No se puede eliminar el almacén porque tiene productos asociados'];
            } else {
                if ($this->stockModel->eliminarAlmacen($id_almacen)) {
                    $_SESSION['mensaje'] = 'Almacén eliminado correctamente';
                } else {
                    $_SESSION['errores'] = ['Error al eliminar el almacén'];
                }
            }
        } catch (Exception $e) {
            $_SESSION['errores'] = ['Error interno del servidor'];
            error_log("Error al eliminar almacén: " . $e->getMessage());
        }
        
        header('Location: ../../Controller/stock/ListarAlmacenesController.php');
        exit();
    }
}

// Ejecutar controlador
if (isset($conn)) {
    $controller = new EliminarAlmacenController($conn);
    $controller->eliminarAlmacen();
}
?>