<?php


require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/proveedor/proveedores.php";



class ListarProveedoresEliminadosController {
    private $proveedorModel;


    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
        nivelRequerido([1,2,3]);
    }

    public function listarProveedoresEliminados() {

        $proveedores = $this->proveedorModel->obtenerProveedoresEliminados();

        require_once __DIR__ . "/../../Views/proveedores/listarProveedoresEliminadosView.php";
    }
}

$controller = new ListarProveedoresEliminadosController($conn);
$controller->listarProveedoresEliminados();
