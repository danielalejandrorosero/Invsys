<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/productos/productos.php';

class AgregarCategoriaController {
    private $productoModel;

    public function __construct($conn) {
        $this->productoModel = new productos($conn);
    }

    public function agregarCategoria() {
        nivelRequerido([1,2]);
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_categoria'])) {
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');

            $nombre = ucwords(strtolower($nombre));

            // Validaciones
            if (strlen($nombre) < 3 || preg_match('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/u', $nombre)) {
                $error['nombre'] = 'Nombre inválido. Debe tener al menos 3 caracteres y no símbolos raros.';
            }

            if (strlen($descripcion) > 500) {
                $error['descripcion'] = 'La descripción no puede exceder 500 caracteres.';
            }

            // Verificar si ya existe
            if (empty($error) && $this->productoModel->existeCategoria($nombre)) {
                $error['nombre'] = 'Ya existe una categoría con ese nombre.';
            }

            if (empty($error)) {
                try {
                    if ($this->productoModel->crearCategoria($nombre, $descripcion)) {
                        $_SESSION['mensaje'] = 'Categoría creada correctamente';
                        header('Location: ../../Controller/productos/ListarCategoriasController.php');
                        exit();
                    } else {
                        $error['general'] = 'Error al crear la categoría. Intente nuevamente.';
                    }
                } catch (Exception $e) {
                    $error['general'] = 'Error interno del servidor.';
                    error_log("Error al crear categoría: " . $e->getMessage());
                }
            }
        }

        require_once __DIR__ . '/../../Views/productos/agregarCategoriaVista.php';
    }
}

// Ejecutar controlador
if (isset($conn)) {
    $controller = new AgregarCategoriaController($conn);
    $controller->agregarCategoria();
}
?>