<?php

require_once '../config/cargarConfig.php';

$error = [];

nivelRequerido(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['ver_inventario'])) {
        $id_almacen = (int) $_POST['id_almacen'];

        $stmt = $conn->prepare("SELECT p.nombre AS producto, sa.cantidad_disponible AS cantidad 
                                FROM stock_almacen sa
                                JOIN productos p ON sa.id_producto = p.id_producto
                                WHERE sa.id_almacen = ?");
        $stmt->bind_param("i", $id_almacen);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $inventario = [];
        while ($row = $result->fetch_assoc()) {
            $inventario[] = $row;
        }
        
        $stmt->close();

        print_r($inventario);
    }

    if (isset($_POST['ajustar_stock'])) {
        $id_producto = (int) $_POST['id_producto'];
        $id_almacen = (int) $_POST['id_almacen'];
        $cantidad = (int) $_POST['cantidad'];

        if ($cantidad < 0) {
            $error[] = "La cantidad debe ser mayor o igual a 0.";
        }

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE stock_almacen SET cantidad_disponible = ? WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen);
            $resultado = $stmt->execute();
            $stmt->close();

            echo $resultado ? "Stock actualizado correctamente." : "Error al actualizar stock.";
        } else {
            print_r($error);
        }
    }
}

// Obtener lista de almacenes
$stmt = $conn->query("SELECT id_almacen, nombre FROM almacenes");
$almacenes = $stmt->fetch_all(MYSQLI_ASSOC);

// Obtener lista de productos
$stmt = $conn->query("SELECT id_producto, nombre FROM productos");
$productos = $stmt->fetch_all(MYSQLI_ASSOC);

?>
