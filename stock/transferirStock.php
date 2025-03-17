<?php
require_once '../config/cargarConfig.php';

$error = [];

nivelRequerido(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transferirStock'])) {

    $camposRequeridos = ['id_producto', 'id_almacen_origen', 'id_almacen_destino', 'cantidad', 'id_usuario'];
    validarCampos($camposRequeridos);

    $id_producto = (int) $_POST['id_producto'];
    $id_almacen_origen = (int) $_POST['id_almacen_origen'];
    $id_almacen_destino = (int) $_POST['id_almacen_destino'];
    $cantidad = (int) $_POST['cantidad'];
    $id_usuario = (int) $_POST['id_usuario'];

    if ($id_almacen_origen == $id_almacen_destino) {
        $error[] = "Error: El almacén de origen y destino no pueden ser el mismo.";
    }

    if ($cantidad <= 0) {
        $error[] = "Error: La cantidad de productos a transferir debe ser mayor a 0.";
    }

    if (empty($error)) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("SELECT cantidad_disponible FROM stock_almacen WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("ii", $id_producto, $id_almacen_origen);
            $stmt->execute();
            $stmt->bind_result($stock_actual);
            
            if (!$stmt->fetch()) {
                throw new Exception("Error: No se encontró stock en el almacén de origen.");
            }
            $stmt->close();

            if ($stock_actual < $cantidad) {
                throw new Exception("Error: Stock insuficiente en el almacén de origen.");
            }

            $stmt = $conn->prepare("UPDATE stock_almacen SET cantidad_disponible = cantidad_disponible - ? WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen_origen);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en almacén de origen.");
            }

            $stmt = $conn->prepare("INSERT INTO stock_almacen (id_producto, id_almacen, cantidad_disponible) 
                                    VALUES (?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible + ?");
            $stmt->bind_param("iiii", $id_producto, $id_almacen_destino, $cantidad, $cantidad);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en almacén de destino.");
            }

            $stmt = $conn->prepare("INSERT INTO movimientos_stock (id_producto, tipo_movimiento, cantidad, id_almacen_origen, id_almacen_destino, fecha_movimiento, id_usuario) 
                                    VALUES (?, 'transferencia', ?, ?, ?, NOW(), ?)");
            $stmt->bind_param("iiiii", $id_producto, $cantidad, $id_almacen_origen, $id_almacen_destino, $id_usuario);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al registrar el movimiento de stock.");
            }

            $sql = "SELECT 
                        p.nombre AS producto,
                        a_origen.nombre AS almacen_origen,
                        a_destino.nombre AS almacen_destino,
                        u.nombre AS usuario
                    FROM productos p
                    JOIN almacenes a_origen ON a_origen.id_almacen = ?
                    JOIN almacenes a_destino ON a_destino.id_almacen = ?
                    JOIN usuarios u ON u.id_usuario = ?
                    WHERE p.id_producto = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $id_producto, $id_almacen_origen, $id_almacen_destino, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            echo "<h2>Transferencia realizada con éxito</h2>";
            echo "<p>Producto: " . htmlspecialchars($row['producto']) . "</p>";
            echo "<p>Almacén de Origen: " . htmlspecialchars($row['almacen_origen']) . "</p>";
            echo "<p>Almacén de Destino: " . htmlspecialchars($row['almacen_destino']) . "</p>";
            echo "<p>Usuario: " . htmlspecialchars($row['usuario']) . "</p>";
            echo "<p>Cantidad Transferida: " . htmlspecialchars($cantidad) . "</p>";
        
        } catch (Exception $e) {
            $conn->rollback();
            echo "<h2>Error en la transferencia</h2>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<h2>Errores encontrados</h2>";
        foreach ($error as $err) {
            echo "<p>" . htmlspecialchars($err) . "</p>";
        }
    }
}
?>
