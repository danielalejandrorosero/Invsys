<?php


require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class EditarProductoController
{
    private $productoModel;

    public function __construct($conn)
    {
        $this->productoModel = new Productos($conn);
        nivelRequerido([1,2]);
    }

    public function editarProducto()
    {
        $error = [];
        $producto = null;

        // Verificar si se ha proporcionado un ID de producto
        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            header(
                "Location: ../../Controller/productos/ListarProductosController.php"
            );
            exit();
        }

        $id_producto = (int) $_GET["id"];
        $producto = $this->productoModel->obtenerProductoPorId($id_producto);

        if (!$producto) {
            $_SESSION["errores"] = ["El producto no existe"];
            header(
                "Location: ../../Controller/productos/ListarProductosController.php"
            );
            exit();
        }

        // Procesar el formulario cuando se envía
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["editarProducto"])
        ) {
            $nombre = htmlspecialchars(trim($_POST["nombre"]));
            $codigo = htmlspecialchars(trim($_POST["codigo"]));
            $sku = htmlspecialchars(trim($_POST["sku"]));
            $descripcion = htmlspecialchars(trim($_POST["descripcion"]));
            $precio_compra = filter_var(
                str_replace(['$', ","], "", $_POST["precio_compra"]),
                FILTER_VALIDATE_FLOAT
            );
            $precio_venta = filter_var(
                str_replace(['$', ","], "", $_POST["precio_venta"]),
                FILTER_VALIDATE_FLOAT
            );
            $id_unidad_medida = (int) $_POST["id_unidad_medida"];
            $stock_minimo = (int) $_POST["stock_minimo"];
            $stock_maximo = (int) $_POST["stock_maximo"];
            $id_categoria = (int) $_POST["id_categoria"];
            $id_proveedor = (int) $_POST["id_proveedor"];

            if (
                !$precio_compra ||
                $precio_compra <= 0 ||
                !$precio_venta ||
                $precio_venta <= 0
            ) {
                $error[] = "El precio de compra y venta deben ser mayores a 0.";
            }

            if ($stock_maximo < $stock_minimo) {
                $error[] =
                    "El stock máximo debe ser mayor o igual al stock mínimo.";
            }

            if (!$this->productoModel->categoriaExiste($id_categoria)) {
                $error[] = "La categoría no existe.";
            }

            if (!$this->productoModel->proveedorExiste($id_proveedor)) {
                $error[] = "El proveedor no existe.";
            }

            if (!$this->productoModel->unidadMedidaExiste($id_unidad_medida)) {
                $error[] = "La unidad de medida no existe.";
            }

            // Verificar código único (excepto para el producto actual)
            if (
                $this->productoModel->codigoExisteExcepto($codigo, $id_producto)
            ) {
                $error[] = "El código ya existe en otro producto.";
            }

            // Verificar SKU único (excepto para el producto actual)
            if ($this->productoModel->skuExisteExcepto($sku, $id_producto)) {
                $error[] = "El SKU ya existe en otro producto.";
            }

            if (empty($error)) {
                if (
                    $this->productoModel->actualizarProducto(
                        $id_producto,
                        $nombre,
                        $codigo,
                        $sku,
                        $descripcion,
                        $precio_compra,
                        $precio_venta,
                        $id_unidad_medida,
                        $stock_minimo,
                        $stock_maximo,
                        $id_categoria,
                        $id_proveedor,
                        null
                    )
                ) {
                    $_SESSION["mensaje"] =
                        "Producto actualizado correctamente.";
                    header(
                        "Location: ../../Controller/productos/ListarProductosController.php"
                    );
                    exit();
                } else {
                    $error[] = "Error al actualizar el producto.";
                }
            }

            if (!empty($error)) {
                $_SESSION["errores"] = $error;
            }
        }

        $categorias = $this->productoModel->obtenerCategorias();
        $proveedores = $this->productoModel->obtenerProveedores();
        $unidades_medida = $this->productoModel->obtenerUnidadesMedida();

        require_once __DIR__ . "/../../Views/productos/editarProductoView.php";
    }
}

$controller = new EditarProductoController($conn);
$controller->editarProducto();
