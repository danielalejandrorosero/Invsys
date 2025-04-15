<?php
// debugear
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";

require_once __DIR__ . "/../../Models/productos/productos.php";

class AgregarProductoController
{
    private $productoModel;

    public function __construct($conn)
    {
        $this->productoModel = new Productos($conn);
    }

    public function agregarProducto()
    {
        nivelRequerido(1);
        $error = [];

        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["agregarProducto"])
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

            if ($this->productoModel->codigoExiste($codigo)) {
                $error[] = "El código ya existe.";
            }

            if ($this->productoModel->skuExiste($sku)) {
                $error[] = "El SKU ya existe.";
            }

            if (empty($error)) {
                if (
                    $this->productoModel->agregarProducto(
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
                        $id_proveedor
                    )
                ) {
                    $_SESSION["mensaje"] = "Producto agregado correctamente.";
                    header(
                        "Location: ../../Controller/productos/ListarProductosController.php"
                    );
                    exit();
                } else {
                    $error[] = "Error al agregar el producto.";
                }
            }

            if (!empty($error)) {
                $_SESSION["errores"] = $error;
            }
        }

        // cargamos los datos para usar los slect
        $categorias = $this->productoModel->obtenerCategorias();
        $proveedores = $this->productoModel->obtenerProveedores();
        $unidades_medida = $this->productoModel->obtenerUnidadesMedida();

        require_once __DIR__ . "/../../Views/productos/agregarProductoView.php";
    }
}

$controller = new AgregarProductoController($conn);
$controller->agregarProducto();
?>
