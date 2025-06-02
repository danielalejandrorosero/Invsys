<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';



class RestaurarProveedorController {
    private $proveedorModel;


    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
    }

    public function restaurarProveedor() {
        nivelRequerido(1);

        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            header("Location: ../../Controller/proveedores/listarProveedores.php");
            exit();
        }

        $id_proveedor = (int) $_GET["id"];
        
        // Verificar que el proveedor exista
        $proveedor = $this->proveedorModel->obtenerProveedorPorId($id_proveedor);
        
        if (!$proveedor) {
            $_SESSION["errores"] = ["El proveedor no existe"];
            header("Location: ../../Controller/proveedores/listarProveedores.php");
            exit();
        }

        // Restaurar el proveedor
        if ($this->proveedorModel->restaurarProveedor($id_proveedor)) {
            $_SESSION["mensaje"] = "Proveedor restaurado correctamente";
        } else {
            $_SESSION["errores"] = ["Error al restaurar el proveedor. ID: " . $id_proveedor];
        }

        // Redireccionar al listado
        header("Location: ../../Controller/proveedores/listarProveedores.php");
        exit();
    }
}


$controller = new RestaurarProveedorController($conn);
$controller->restaurarProveedor();