<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';

class AgregarProveedorController {
    private $proveedorModel;
    
    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
    }
    
    public function agregarProveedor() {
        nivelRequerido(1);
        $error = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_proveedor'])) {
            
            $nombre = htmlspecialchars(trim($_POST['nombre']));
            $contacto = htmlspecialchars(trim($_POST['contacto'])); // Agregada esta línea
            $direccion = htmlspecialchars(trim($_POST['direccion']));
            $telefono = htmlspecialchars(trim($_POST['telefono']));
            $email = htmlspecialchars(trim($_POST['email']));
            
            // Validaciones de campos una por una
            if (empty($nombre)) {
                $error['nombre'] = 'El nombre del proveedor es obligatorio';
            }
            
            if (empty($contacto)) {
                $error['contacto'] = 'El contacto del proveedor es obligatorio';
            }
            
            if (empty($direccion)) {
                $error['direccion'] = 'La dirección del proveedor es obligatoria';
            }
            
            if (empty($telefono)) {
                $error['telefono'] = 'El teléfono del proveedor es obligatorio';
            }
            
            if (empty($email)) {
                $error['email'] = 'El email del proveedor es obligatorio';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error['email'] = 'El email del proveedor tiene un formato incorrecto';
            }
            
            // Validaciones adicionales opcionales
            if (!empty($telefono) && !preg_match('/^[0-9\-\+\(\)\s]+$/', $telefono)) {
                $error['telefono'] = 'El teléfono solo puede contener números, espacios, guiones y paréntesis';
            }
            
            if (empty($error)) {
                try {

                    if ($this->proveedorModel->registrarProveedor($nombre, $contacto, $direccion, $telefono, $email)) {
                        $_SESSION['mensaje'] = 'Proveedor agregado correctamente';
                        header('Location: ../../Controller/proveedores/ListarProveedoresController.php');
                        exit();
                    } else {
                        $error['general'] = 'Error al agregar el proveedor. Intente nuevamente.';
                    }
                } catch (Exception $e) {
                    $error['general'] = 'Error en el sistema: ' . $e->getMessage();
                }
            }
        }
        
        require_once __DIR__ . '/../../Views/proveedores/agregarProveedorVista.php';
    }
}

$controller = new AgregarProveedorController($conn);
$controller->agregarProveedor();
?>