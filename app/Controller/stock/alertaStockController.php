<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../../public/index.php");
    exit();
}

// Inicializar el modelo
$stock = new Stock($conn);

// Obtener productos con bajo stock
$productosBajoStock = $stock->obtenerProductosBajoStock();

// Incluir la vista
require_once __DIR__ . '/../../Views/stock/alertaStock.php';
?>