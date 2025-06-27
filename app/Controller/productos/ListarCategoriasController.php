<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/productos/productos.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

class ListarCategoriasController {

    private $productosModel;


    public function __construct($conn)
    {
        $this->productosModel = new Productos($conn);
    }

    public function listarCategorias()
    {
        return $this->productosModel->obtenerCategorias();
    }
}

// Ejecutar controlador y obtener las categorías
if (isset($conn)) {
    $controller = new ListarCategoriasController($conn);
    $categorias = $controller->listarCategorias();
    // Aquí puedes requerir la vista y pasarle $categorias
    require_once __DIR__ . '/../../Views/productos/listarCategoriasVista.php';
}

$controller = new ListarCategoriasController($conn);
$categorias = $controller->listarCategorias();