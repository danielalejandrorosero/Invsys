<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';

class EditarProveedorController {
    private $proveedorModel;

    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
    }


    public function editarProveedor() {
        nivelRequerido(1);
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_proveedor'])) {
            
        }
    }
}