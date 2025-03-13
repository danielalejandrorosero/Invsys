<?php

require_once '../config/cargarConfig.php';



$error = [];

nivelRequerido(1);

// Verificar si la petición es POST y contiene 'eliminarProducto'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarProducto'])) {
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    
    if (!$id_producto || $id_producto <= 0) {
        $error[] = "ID de producto inválido.";
    }

    // Solo intentamos eliminar si no hay errores previos
    if (empty($error)) {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Producto eliminado exitosamente!";
            } else {
                error_log("Error al eliminar producto: " . $stmt->error);
                $error[] = "No se pudo eliminar el producto. Puede que no exista.";
            }

            $stmt->close();
        } else {
            error_log("Error en la consulta SQL: " . $conn->error);
            $error[] = "Error interno, intenta más tarde.";
        }
    }
}

// Mostrar errores si los hay
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p style='color:red;'>$err</p>";
    }
}

?>
