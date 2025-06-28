<?php

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class EliminarCategoriaController {
    private $productoModel;

    public function __construct($conn) {
        $this->productoModel = new Productos($conn);
        nivelRequerido([1,2]);
    }

    public function eliminarCategoria() {
        $error = [];
        $categoria = null;

        // Verificar si se ha proporcionado un ID de categoría
            if (!isset($_GET["id"]) || empty($_GET["id"])) {
            $_SESSION["errores"] = ["ID de categoría no proporcionado"];
            header("Location: ../../Controller/productos/ListarCategoriasController.php");
                exit();
            }
    
            $id_categoria = (int) $_GET["id"];
        $categoria = $this->productoModel->obtenerCategoriaPorId($id_categoria);
    
            if (!$categoria) {
                $_SESSION["errores"] = ["La categoría no existe"];
            header("Location: ../../Controller/productos/ListarCategoriasController.php");
                exit();
            } 
    
        // Verificar si la categoría está en uso
        if ($this->productoModel->categoriaEnUso($id_categoria)) {
            $error['en_uso'] = "No se puede eliminar la categoría porque hay productos que la utilizan";
        }

        // Procesar la eliminación solo si no hay errores y se confirma
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirmar_eliminacion"]) && empty($error)) {
            if ($this->productoModel->eliminarCategoria($id_categoria)) {
                $_SESSION["mensaje"] = "Categoría eliminada correctamente";
            } else {
                $_SESSION["errores"] = ["Error al eliminar la categoría"];
            }
            header("Location: ../../Controller/productos/ListarCategoriasController.php");
            exit();
        }

        // Cargar la vista de confirmación (siempre se muestra)
        require_once __DIR__ . '/../../Views/productos/eliminarCategoriaVista.php';
    }
}

// Ejecutar controlador
if (isset($conn)) {
    $controller = new EliminarCategoriaController($conn);
    $controller->eliminarCategoria();
}
?>