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
        $proveedor = null;

        // Verificar que existe el ID en la URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: ../../Controller/proveedores/ListarProveedoresController.php');
            exit();
        }

        $id_proveedor = (int) $_GET['id'];
        $proveedor = $this->proveedorModel->obtenerProveedorPorId($id_proveedor);

        // Verificar que el proveedor existe
        if (!$proveedor) {
            $_SESSION['errores'] = ['El proveedor no existe'];
            header('Location: ../../Controller/proveedores/ListarProveedoresController.php');
            exit();
        }

        // Procesar formulario cuando se envía
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_proveedor'])) {
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
            $contacto = htmlspecialchars(trim($_POST['contacto'] ?? ''));
            $direccion = htmlspecialchars(trim($_POST['direccion'] ?? ''));
            $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
            $email = htmlspecialchars(trim($_POST['email'] ?? ''));

            // Validaciones de datos
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
            }

            // Sanitizar y validar email
            if (!empty($email)) {
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error['email'] = 'El email del proveedor es inválido';
                }

                // Verificar que el email no esté en uso por otro proveedor
                if ($this->proveedorModel->emailExisteExcepto($email, $id_proveedor)) {
                    $error['email'] = 'El email del proveedor ya está en uso';
                }
            }

            // Si pasa todas las validaciones, actualizar
            if (empty($error)) {
                if ($this->proveedorModel->actualizarProveedor($id_proveedor, $nombre, $contacto, $direccion, $telefono, $email)) {
                    $_SESSION['mensaje'] = 'Proveedor actualizado correctamente';
                    header('Location: ../../Controller/proveedores/listarProveedores.php');
                    exit();
                } else {
                    $error['general'] = 'Error al actualizar el proveedor';
                }
            }
        }

        // Cargar la vista (SIEMPRE se debe cargar después del procesamiento)
        require_once __DIR__ . '/../../Views/proveedores/editarProveedorView.php';
    }
}

// Ejecutar el controlador
$controller = new EditarProveedorController($conn);
$controller->editarProveedor();
?>