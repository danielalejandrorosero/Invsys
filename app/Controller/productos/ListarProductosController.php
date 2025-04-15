<?php

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

        $productos = $this->productosModel->obtenerTodosProductos();

        // cargar vista
        require_once __DIR__ . "/../../Views/productos/listarProductosView.php";
    }
}

$controller = new ListarProductosController($conn);
$controller->listarProductos();
