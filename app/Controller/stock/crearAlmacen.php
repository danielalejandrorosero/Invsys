<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';



class CrearAlmacenController {
    private $stockModel;
    private const MAX_LENGTH_NOMBRE = 100;
    private const MAX_LENGTH_UBICACION = 200;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }


    public function crearAlmacen() {
        try {
            nivelRequerido(1); // Solo administradores pueden crear almacenes


            $error = [];


            if  (
                $_SERVER["REQUEST_METHOD"] === "POST" &&
                isset($_POST["agregarAlmacen"])
            )  {

                $nombre = htmlspecialchars(trim($_POST["nombre"]));
                $ubicacion = htmlspecialchars(trim($_POST["ubicacion"]));

                // Validaciones
                if (empty($nombre)) {
                    $error[] = "El nombre del almacén no puede estar vacío.";
                } elseif (strlen($nombre) > self::MAX_LENGTH_NOMBRE) {
                    $error[] = "El nombre del almacén no puede tener más de " . self::MAX_LENGTH_NOMBRE . " caracteres.";
                } elseif (!preg_match('/^[a-zA-Z0-9\s\-_]+$/', $nombre)) {
                    $error[] = "El nombre del almacén solo puede contener letras, números, espacios, guiones y guiones bajos.";
                }

                if (empty($ubicacion)) {
                    $error[] = "La ubicación del almacén no puede estar vacía.";
                } elseif (strlen($ubicacion) > self::MAX_LENGTH_UBICACION) {
                    $error[] = "La ubicación del almacén no puede tener más de " . self::MAX_LENGTH_UBICACION . " caracteres.";
                }

                // Verificar duplicados
                if (empty($error) && $this->stockModel->almacenExiste($nombre)) {
                    $error[] = "Ya existe un almacén con ese nombre.";
                }

                if (empty($error)) {
                    // Intentar crear el almacén
                    if ($this->stockModel->crearAlmacen($nombre, $ubicacion)) {
                        $_SESSION["mensaje"] = "Almacén creado correctamente.";
                        require_once __DIR__ . '/../../Views/stock/formularioStock.php';

                        exit();
                    } else {
                        $error[] = "Error al crear el almacén. Por favor, intente nuevamente.";
                    }
                }
            }

            if (!empty($error)) {
                $_SESSION["errores"] = $error;
                $_SESSION["datos_formulario"] = [
                    "nombre" => $nombre ?? "",
                    "ubicacion" => $ubicacion ?? ""
                ];
            }

        } catch (Exception $e) {
            error_log("Error en CrearAlmacenController: " . $e->getMessage());
            $_SESSION["errores"] = ["Ocurrió un error inesperado. Por favor, intente nuevamente más tarde."];
        }
    }
}