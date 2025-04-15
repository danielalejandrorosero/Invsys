<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/productos/productos.php";

class ListarProductosEliminadosController
{
    private $productosModel;

    public function __construct($conn)
    {
        $this->productosModel = new Productos($conn);
    }

    public function listarProductosEliminados()
    {
        nivelRequerido(1);

        $productos = $this->productosModel->obtenerProductosEliminados();

        require_once __DIR__ .
            "/../../Views/productos/listarProductosEliminadosView.php";
    }
}

$controller = new ListarProductosEliminadosController($conn);
$controller->listarProductosEliminados();
?>
