8 <?php

// debugear
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class ListarProductosController
{
    private $productosModel;

    public function __construct($conn)
    {
        $this->productosModel = new Productos($conn);
    }

    public function listarProductos()
    {
        nivelRequerido(1);

        // Parámetros de paginación
        $limit = 10; // Número de productos por página
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Obtener productos y total de productos
        $productos = $this->productosModel->obtenerProductosConPaginacion($limit, $offset);
        $totalProductos = $this->productosModel->contarTotalProductos();

        // Calcular el número total de páginas
        $totalPaginas = ceil($totalProductos / $limit);

        // cargar vista
        require_once __DIR__ . "/../../Views/productos/listarProductosView.php";
    }
}

$controller = new ListarProductosController($conn);
$controller->listarProductos();
