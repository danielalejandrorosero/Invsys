<?php
require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

// debugear
error_reporting(E_ALL);
ini_set("display_errors", 1);

class RestaurarProductoController
{
    private $productosModel;

    public function __construct($conn)
    {
        $this->productosModel = new Productos($conn);
    }

    public function restaurarProducto()
    {
        // validar y e obtener el id del prodcuto

        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            $_SESSION["errores"] = ["ID de producto no proporcionado"];
            header(
                "Location: ../../Controller/productos/ListarProductosEliminadosController.php"
            );
            exit();
        }

        $id_producto = (int) $_GET["id"];
        $producto = $this->productosModel->obtenerProductoPorId($id_producto);

        if (!$producto) {
            $_SESSION["errores"] = ["Producto no encontrado"];
            header(
                "Location: ../../Controller/productos/ListarProductosEliminadosController.php"
            );
            exit();
        }

        // procesar la restauracion del producto
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["confirmarRestaurar"])
        ) {
            if ($this->productosModel->restaurarProducto($id_producto)) {
                $_SESSION["mensajes"] = ["Producto restaurado exitosamente"];
                header(
                    "Location: ../../Controller/productos/ListarProductosController.php"
                );
                exit();
            } else {
                $_SESSION["errores"] = ["Error al restaurar el producto"];
            }
        }

        require_once __DIR__ .
            "/../../Views/productos/restaurarProductoView.php";
    }
}

$controller = new RestaurarProductoController($conn);
$controller->restaurarProducto();
