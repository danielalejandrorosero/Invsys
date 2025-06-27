<?php

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";




class EditarCategoriaController {
    private $productoModel;
    public function __construct($conn)
    {
        $this->productoModel = new Productos($conn);
        nivelRequerido([1,2]);
    }


    public function editarCategoria() {

        $error = [];

        $categoria = null;

    // Verificar si se ha proporcionado un ID de producto por get
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            header(
                "Location: ../../Controller/productos/ListarCategoriasController.php"
            );
            exit();
        }

        $id_categoria = (int) $_GET["id"];
        $categoria = $this->productoModel->obtenerCategoriaPorId($id_categoria);

        if (!$categoria) {
            $_SESSION["errores"] = ["La categoría no existe"];
            header(
                "Location: ../../Controller/productos/ListarCategoriasController.php"
            );
            exit();
        } 

        // Procesar el formulario cuando se envía

        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["editarCategoria"])
        ) {
            $nombre = htmlspecialchars(trim($_POST["nombre"]));
            $descripcion = htmlspecialchars(trim($_POST["descripcion"]));

            // Validaciones
            if (strlen($nombre) < 3 || preg_match('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/u', $nombre)) {
                $error['nombre'] = 'Nombre inválido. Debe tener al menos 3 caracteres y no símbolos raros.';
            }

            if (strlen($descripcion) > 500) {
                $error['descripcion'] = 'La descripción no puede exceder 500 caracteres.';
            }

            if (empty($error)) {
                if ($this->productoModel->editarCategoria($id_categoria, $nombre, $descripcion)) {

                $_SESSION["mensaje"] = "Categoría actualizada correctamente.";

                } else {
                    $_SESSION["errores"] = ["Error al editar la categoría"];
                }
                header("Location: ../../Controller/productos/ListarCategoriasController.php");
                exit();
            }
        }

        require_once __DIR__ . '/../../Views/productos/editarCategoriaVista.php';
    }
}

$controller = new EditarCategoriaController($conn);
$controller->editarCategoria();