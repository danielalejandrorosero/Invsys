<?php



require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class EliminarProductoController
{
    private $productosModel;

    public function __construct($conn)
    {
        $this->productosModel = new Productos($conn);
        nivelRequerido([1, 2]);
    }
    public function eliminarProducto()
    {

        // Validar y obtener el ID del producto
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            header(
                "Location: ../../Controller/productos/ListarProductosController.php"
            );
            exit();
        }

        $id_producto = (int) $_GET["id"];

        // Verificar que el producto existe
        $producto = $this->productosModel->obtenerProductoPorId($id_producto);

        if (!$producto) {
            $_SESSION["errores"] = ["El producto no existe"];
            header(
                "Location: ../../Controller/productos/ListarProductosController.php"
            );
            exit();
        }

        // Eliminar directamente (sin necesidad de POST o confirmación adicional)
        if ($this->productosModel->eliminarProducto($id_producto)) {
            $_SESSION["mensaje"] = "Producto eliminado correctamente";
        } else {
            $_SESSION["errores"] = ["Error al eliminar el producto"];
        }

        // Siempre redireccionar al listado
        header(
            "Location: ../../Controller/productos/ListarProductosController.php"
        );
        exit();
    }
}

$controller = new EliminarProductoController($conn);

$controller->eliminarProducto();
