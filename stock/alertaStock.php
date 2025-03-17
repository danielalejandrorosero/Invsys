<?php

require_once '../config/cargarConfig.php';

nivelRequerido(1); // solo le aparecerá a usuarios con nivel 1

function obtenerProductosBajoStock() {
    global $conn;
    
    $sql = "SELECT p.id_producto, p.nombre, sa.cantidad_disponible, p.stock_minimo
            FROM stock_almacen sa
            JOIN productos p ON sa.id_producto = p.id_producto
            WHERE sa.cantidad_disponible <= p.stock_minimo";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    return $productos;
}

// hacer una ventana emergenete que muestre los productos con stock bajo
?>


