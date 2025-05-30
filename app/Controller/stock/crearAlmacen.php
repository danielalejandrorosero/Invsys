<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';


class CrearAlmacenController {

    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }

    public function crearAlmacen() {
        
        nivelRequerido(1); // Solo administradores pueden crear almacenes

        $error = [];

        // Verificar token CSRF en solicitudes POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar token CSRF
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $error[] = "Error de seguridad: token CSRF inválido.";
            } else {
                $nombre_almacen = isset($_POST['nombre_almacen']) ? trim($_POST['nombre_almacen']) : null;
                $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : null;
                
                // validacion de datos
                if (empty($nombre_almacen)) {
                    $error[] = "El nombre del almacen es obligatorio.";
                }

                if (empty($direccion)) {
                    $error[] = "La direccion del almacen es obligatoria.";
                }

                if (empty($error)) {
                    // hacemos la insercion en la db
                    try {
                        $id_almacen = $this->stockModel->crearAlmacen($nombre_almacen, $direccion);
                        if ($id_almacen) {
                            $_SESSION["mensaje"] = "Almacen creado correctamente : $id_almacen.";
                            header("Location: ../../Views/stock/crearAlmacenVista.php?success=1");
                            exit();
                        } else {
                            $error[] = "Error al crear el almacen.";
                        }
                    } catch (Exception $e) {
                        error_log("Error en CrearAlmacenController: " . $e->getMessage());
                        $error[] = "Error al crear el almacen: " . $e->getMessage();
                    }
                }

                if (!empty($error)) {
                    $_SESSION["errores"] = $error;
                }
            }
        }
        
        // Generar nuevo token CSRF para el formulario
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

$controllerAlmacen = new CrearAlmacenController($conn);
$controllerAlmacen->crearAlmacen();
