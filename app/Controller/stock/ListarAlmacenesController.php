<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class ListarAlmacenesController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }

    public function listarAlmacenes() {
        nivelRequerido(1);
        
        try {
            $almacenes = $this->stockModel->obtenerAlmacenesActivos();
            require_once __DIR__ . '/../../Views/stock/listarAlmacenesVista.php';
        } catch (Exception $e) {
            $_SESSION['errores'] = ['Error al cargar los almacenes'];
            error_log("Error al listar almacenes: " . $e->getMessage());
            require_once __DIR__ . '/../../Views/stock/listarAlmacenesVista.php';
        }
    }
}

// Ejecutar controlador
if (isset($conn)) {
    $controller = new ListarAlmacenesController($conn);
    $controller->listarAlmacenes();
}
?>