<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';

// debugear


class RestaurarProveedorController {
    private $proveedorModel;
    
    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
        nivelRequerido([1,2]);
    }
    
    public function restaurarProveedor() {
        
        // validar y obtener el id del proveedor
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            header("Location: ../../Controller/proveedores/ListarProveedoresEliminadosController.php");
            exit();
        }
        
        $id_proveedor = (int) $_GET["id"];
        $proveedor = $this->proveedorModel->obtenerProveedorPorId($id_proveedor);
        
        if (!$proveedor) {
            header("Location: ../../Controller/proveedores/ListarProveedoresEliminadosController.php");
            exit();
        }
        
        // procesar la restauracion del proveedor
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirmarRestaurar"])) {
            if ($this->proveedorModel->restaurarProveedor($id_proveedor)) {
                $_SESSION["mensaje"] = "Proveedor restaurado exitosamente";
                header("Location: ../../Controller/proveedores/listarProveedores.php");
                exit();
            } else {
                $_SESSION["errores"] = ["Error al restaurar el proveedor"];
            }
        }
        
        require_once __DIR__ . "/../../Views/proveedores/restaurarProveedorVista.php";
    }
}

$controller = new RestaurarProveedorController($conn);
$controller->restaurarProveedor();