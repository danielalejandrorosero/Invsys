<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class EditarAlmacenController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
        nivelRequerido([1]);
    }

    public function editarAlmacen() {
        nivelRequerido([1,2]);
        $error = [];
        $almacen = null;

        // Verificar que existe el ID en la URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: ../../Controller/stock/ListarAlmacenesController.php');
            exit();
        }

        $id_almacen = (int) $_GET['id'];
        $almacen = $this->stockModel->obtenerAlmacenPorId($id_almacen);

        if (!$almacen) {
            $_SESSION['errores'] = ['El almacén no existe'];
            header('Location: ../../Controller/stock/ListarAlmacenesController.php');   
            exit();
        }

        // Procesar formulario de edición
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_almacen'])) {
            $nombre = trim($_POST['nombre'] ?? '');
            $ubicacion = trim($_POST['ubicacion'] ?? '');

            $nombre = ucwords(strtolower($nombre));
            $ubicacion = ucfirst(strtolower($ubicacion));

            // Validaciones
            if (strlen($nombre) < 5 || preg_match('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/u', $nombre)) {
                $error['nombre'] = 'Nombre inválido. Debe tener al menos 5 caracteres y no símbolos raros.';
            }

            if (strlen($ubicacion) < 10 || !preg_match('/[a-zA-Z0-9]/', $ubicacion)) {
                $error['ubicacion'] = 'La ubicación debe tener al menos 10 caracteres válidos.';
            }

            if (empty($error)) {
                try {
                    if ($this->stockModel->editarAlmacen($id_almacen, $nombre, $ubicacion)) {
                        $_SESSION['mensaje'] = 'Almacén editado correctamente';
                        header('Location: ../../Controller/stock/ListarAlmacenesController.php');
                        exit();
                    } else {
                        $error['general'] = 'Error al editar el almacén. Intente nuevamente.';
                    }
                } catch (Exception $e) {
                    $error['general'] = 'Error interno del servidor.';
                    error_log("Error al editar almacén: " . $e->getMessage());
                }
            }
        }

        // Cargar vista
        require_once __DIR__ . '/../../Views/stock/editarAlmacenVista.php';
    }
}

// Ejecutar controlador
if (isset($conn)) {
    $controller = new EditarAlmacenController($conn);
    $controller->editarAlmacen();
}
?>