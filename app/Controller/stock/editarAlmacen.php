<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';




class EditarAlmacenController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }



    public function editarAlmacen() {
        nivelRequerido(1);
        $error = [];
        $almacen = null;





        $id_almacen = (int) $_GET['id'];

        $almacen = $this->stockModel->obtenerAlmacenPorId($id_almacen);



        // Verificar que existe el ID en la URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: ../../Controller/stock/ListarAlmacenesController.php');
            exit();
        }


        if (!$almacen) {
            $_SESSION['errores'] = ['El almacen no existe'];
            header('Location: ../../Controller/stock/ListarAlmacenesController.php');   
            exit();
        }
    }
}