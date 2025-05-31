<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class BuscarProductosController
{
    private $productoModel;

    public function __construct($conn)
    {
        $this->productoModel = new Productos($conn);
    }

    public function buscarProductos()
    {
        nivelRequerido(1);

        // Obtener parámetros de búsqueda
        $nombre = isset($_GET["nombre"]) ? $_GET["nombre"] : null;
        $codigo = isset($_GET["codigo"]) ? $_GET["codigo"] : null;
        $sku = isset($_GET["sku"]) ? $_GET["sku"] : null;
        $categoria = isset($_GET["categoria"]) ? $_GET["categoria"] : null;
        $unidad_medida = isset($_GET["unidad_medida"])
            ? $_GET["unidad_medida"]
            : null;

        // Obtener categorías y unidades de medida para los select
        $categorias = $this->productoModel->obtenerCategorias();
        $unidades_medida = $this->productoModel->obtenerUnidadesMedida();

        // Inicializar variable productos
        $productos = [];

        // Realizar búsqueda solo si se envió el formulario
        if (
            $_SERVER["REQUEST_METHOD"] === "GET" &&
            !empty($_GET) &&
            (isset($_GET["nombre"]) ||
                isset($_GET["codigo"]) ||
                isset($_GET["sku"]) ||
                isset($_GET["categoria"]) ||
                isset($_GET["unidad_medida"]))
        ) {
            $productos = $this->productoModel->buscarProductos(
                $nombre,
                $codigo,
                $sku,
                $categoria,
                $unidad_medida
            );
        }

        // Cargar vista con los resultados
        require_once __DIR__ .
            "/../../Views/productos/buscarProductosVista.php";
    }
}

$controller = new BuscarProductosController($conn);
$controller->buscarProductos();
