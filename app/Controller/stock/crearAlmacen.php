<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class CrearAlmacenController {

    private $stockModel;

    

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }

    public function crearAlmacen() {
        nivelRequerido(1); 

        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_almacen'])) {


            $nombre    = trim($_POST['nombre'] ?? '');
            $ubicacion = trim($_POST['ubicacion'] ?? '');


            $nombre    = ucwords(strtolower($nombre));     
            $ubicacion = ucfirst(strtolower($ubicacion));  


            if (strlen($nombre) < 5 || preg_match('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/u', $nombre)) {
                $error['nombre'] = 'Nombre inválido. Debe tener al menos 5 caracteres y no símbolos raros.';
            }

            if (strlen($ubicacion) < 10 || !preg_match('/[a-zA-Z0-9]/', $ubicacion)) {
                $error['ubicacion'] = 'La ubicación debe tener al menos 10 caracteres válidos.';
            }


            if (empty($error)) {
                try {
                    if ($this->stockModel->crearAlmacen($nombre, $ubicacion)) {
                        $_SESSION['mensaje'] = 'Almacén creado correctamente';
                        header('Location: ../../Controller/stock/verInventarioController.php');
                        exit();
                    } else {
                        $error['general'] = 'Error al crear el almacén. Intente nuevamente.';
                    }
                } catch (Exception $e) {
                    $error['general'] = 'Error en el sistema: ' . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../../Views/stock/crearAlmacenVista.php';
    }
}

// Instancia y ejecución
$controller = new CrearAlmacenController($conn);
$controller->crearAlmacen();
