<?php

class Stock {
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerProductosBajoStock() {
        try {
            $sql = "SELECT p.id_producto, p.nombre, sa.cantidad_disponible, p.stock_minimo
                    FROM stock_almacen sa
                    JOIN productos p ON sa.id_producto = p.id_producto
                    WHERE sa.cantidad_disponible <= p.stock_minimo";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $productos = [];
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
            return $productos;
        } catch (Exception $e) {
            error_log("Error al obtener productos con stock bajo: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
    public function verInventario($id_almacen) {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    p.id_producto,
                    p.nombre AS producto,
                    sa.cantidad_disponible AS cantidad,
                    p.stock_minimo,
                    p.stock_maximo
                FROM stock_almacen sa
                JOIN productos p ON sa.id_producto = p.id_producto
                WHERE sa.id_almacen = ? AND p.estado = 'activo'
            ");
            $stmt->bind_param("i", $id_almacen);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $inventario = [];
            while ($row = $result->fetch_assoc()) {
                $inventario[] = $row;
            }
            return $inventario;
        } catch (Exception $e) {
            error_log("Error al obtener inventario: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function obtenerAlmacenes() {
        $stmt = null;
        try {
            $stmt = $this->conn->query("SELECT id_almacen, nombre FROM almacenes");
            return $stmt->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener almacenes: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function ajustarStock($id_producto, $id_almacen, $cantidad) {
        $stmt = null;
        try {
            if ($cantidad < 0) {
                throw new Exception("La cantidad debe ser mayor o igual a 0.");
            }
    
            // Verificar si el producto ya existe en el almacén
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM stock_almacen WHERE id_producto = ? AND id_almacen = ?");
            $checkStmt->bind_param("ii", $id_producto, $id_almacen);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $exists = $result->fetch_row()[0] > 0;
            $checkStmt->close();
            
            if ($exists) {
                // Si existe, actualizar
                $sql = "UPDATE stock_almacen SET cantidad_disponible = ? WHERE id_producto = ? AND id_almacen = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen);
            } else {
                // Si no existe, insertar
                $sql = "INSERT INTO stock_almacen (id_producto, id_almacen, cantidad_disponible) VALUES (?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("iii", $id_producto, $id_almacen, $cantidad);
            }
            
            $resultado = $stmt->execute();
            return $resultado;
        } catch (Exception $e) {
            error_log("Error al ajustar el stock: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }



    public function obtenerAlmacenesConProducto($id_producto) {
        try {
            $stmt = $this->conn->prepare("SELECT a.id_almacen, a.nombre 
                                          FROM stock_almacen sa
                                          JOIN almacenes a ON sa.id_almacen = a.id_almacen
                                          WHERE sa.id_producto = ?");
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener almacenes con producto: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMovimientos($almacen = null, $producto = null, $tipo = null) {
        try {
            $sql = "SELECT 
                        ms.id_movimiento, 
                        p.nombre AS producto, 
                        ms.tipo_movimiento, 
                        ms.cantidad, 
                        ms.fecha_movimiento,
                        a_origen.nombre AS almacen_origen,
                        a_destino.nombre AS almacen_destino,
                        COALESCE(u.nombre, 'Sin usuario') AS usuario
                    FROM movimientos_stock ms
                    JOIN productos p ON ms.id_producto = p.id_producto
                    LEFT JOIN almacenes a_origen ON ms.id_almacen_origen = a_origen.id_almacen
                    LEFT JOIN almacenes a_destino ON ms.id_almacen_destino = a_destino.id_almacen
                    LEFT JOIN usuarios u ON ms.id_usuario = u.id_usuario";

            $where = [];
            $params = [];
            $types = "";

            if (!empty($almacen)) {
                $where[] = "(a_origen.nombre LIKE ? OR a_destino.nombre LIKE ?)";
                $params[] = "%$almacen%";
                $params[] = "%$almacen%";
                $types   .= "ss";
            }

            if (!empty($producto)) {
                $where[] = "p.nombre LIKE ?";
                $params[] = "%$producto%";
                $types   .= "s";
            }

            if (!empty($tipo)) {
                $where[] = "ms.tipo_movimiento LIKE ?";
                $params[] = "%$tipo%";
                $types   .= "s";
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $sql .= " ORDER BY ms.fecha_movimiento DESC";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            error_log("Error al obtener movimientos: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function generarReporte($almacen = null, $categoria = null, $proveedor = null) {
        try {
            $sql = "SELECT 
                p.id_producto,
                p.nombre AS producto,
                a.nombre AS almacen,
                sa.cantidad_disponible AS cantidad,
                p.stock_minimo,
                p.stock_maximo,
                c.nombre AS categoria,
                pr.nombre AS proveedor
            FROM stock_almacen sa
            JOIN productos p ON sa.id_producto = p.id_producto
            JOIN almacenes a ON sa.id_almacen = a.id_almacen
            JOIN categorias c ON p.id_categoria = c.id_categoria
            JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor";

            $where  = [];
            $params = [];
            $types  = "";

            if (!empty($almacen)) {
                $where[] = "a.nombre LIKE ?";
                $params[] = "%{$almacen}%";  
                $types   .= "s";
            }

            if (!empty($categoria)) {
                $where[] = "c.nombre LIKE ?";
                $params[] = "%{$categoria}%";
                $types   .= "s";
            }

            if (!empty($proveedor)) {
                $where[] = "pr.nombre LIKE ?";
                $params[] = "%{$proveedor}%";
                $types   .= "s";
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $sql .= " ORDER BY p.nombre ASC";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la consulta: " . $this->conn->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            error_log("Error al generar reporte: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function obtenerAlmacenOrigen($id_producto) {
        try {
            $stmt = $this->conn->prepare("SELECT a.id_almacen, a.nombre 
            FROM stock_almacen sa
            JOIN almacenes a ON sa.id_almacen = a.id_almacen
            WHERE sa.id_producto = ? AND sa.cantidad_disponible > 0
            LIMIT 1");       
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error al obtener almacen origen: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }


    public function transferirStock($id_producto, $id_almacen_origen, $id_almacen_destino, $cantidad, $id_usuario) {
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare("SELECT cantidad_disponible FROM stock_almacen WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("ii", $id_producto, $id_almacen_origen);
            $stmt->execute();
            $stock_actual = 0;
            $stmt->bind_result($stock_actual);
            if (!$stmt->fetch()) {
                throw new Exception("Error: No se encontró stock en el almacén de origen.");
            }
            $stmt->close();

            if ($stock_actual < $cantidad) {
                throw new Exception("Error: Stock insuficiente en el almacén de origen.");
            }

            $stmt = $this->conn->prepare("UPDATE stock_almacen SET cantidad_disponible = cantidad_disponible - ? WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen_origen);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en el almacén de origen.");
            }

            $stmt = $this->conn->prepare("INSERT INTO stock_almacen 
                                        (id_producto, id_almacen, cantidad_disponible) 
                                        VALUES (?, ?, ?) 
                                        ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible + ?");
            $stmt->bind_param("iiii", $id_producto, $id_almacen_destino, $cantidad, $cantidad);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en el almacén de destino.");
            }

            $stmt = $this->conn->prepare("INSERT INTO movimientos_stock (id_producto, tipo_movimiento, cantidad, id_almacen_origen, id_almacen_destino, fecha_movimiento, id_usuario) VALUES (?, 'transferencia', ?, ?, ?, NOW(), ?)");
            $stmt->bind_param("iiiii", $id_producto, $cantidad, $id_almacen_origen, $id_almacen_destino, $id_usuario);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al registrar el movimiento de stock.");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return $e->getMessage();
        }
    }
}
?>