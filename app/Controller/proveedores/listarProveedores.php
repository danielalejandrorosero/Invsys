<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';


class ListarProveedoresController {

    private $proveedorModel;


    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
        nivelRequerido([1,2,3]);
    }


    public function listarProveedores() {

        // parametros para la paginacion
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;


        // obtener proveedores
        $proveedores = $this->proveedorModel->obtenerProveedoresConPaginacion($limit, $offset);
        $totalProveedores = $this->proveedorModel->contarTotalProveedores();

        // calcular el numero total de paginas
        $totalPaginas = ceil($totalProveedores / $limit);

        // cargar vista
        require_once __DIR__ . '/../../Views/proveedores/listarProveedoresView.php';
    }
}

$controller = new ListarProveedoresController($conn);
$controller->listarProveedores();





//
//$productos = $this->productosModel->obtenerProductosConPaginacion($limit, $offset);
//$totalProductos = $this->productosModel->contarTotalProductos();

//