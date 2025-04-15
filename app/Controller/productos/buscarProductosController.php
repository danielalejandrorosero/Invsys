<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';



require_once __DIR__ . '/../../Models/productos/productos.php';


class BuscarProductosController {



    private $productoModel;


    public function __construct($conn) {
        $this->productoModel = new Productos($conn);
    }

    public function buscarProductos() {
        nivelRequerido(1);



        // obtener los parametor de busqueda desde el get
        $nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : null;
        $codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : null;
        $sku = isset($_GET['sku']) ? trim($_GET['sku']) : null;
        $categoria = isset($_GET['categoria']) ? trim($_GET['categor    ia']) : null;
        $unidad_medida = isset($_GET['unidad_medida']) ? trim($_GET['unidad_medida']) : null;


        // buscar productos


        $productos = $this->productoModel->buscarProductos($nombre, $codigo, $sku, $categoria, $unidad_medida);

        // categoria y unidad de medida
        $categorias = $this->productoModel->obtenerCategorias();
        $unidades_medida = $this->productoModel->obtenerUnidadesMedida();


        // cargar vista

        require_once __DIR__ . '/../../Views/productos/buscarProductosVista.php';   
        
    }
}


$controller = new BuscarProductosController($conn);
$controller->buscarProductos();