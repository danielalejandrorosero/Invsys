<?php

class Stock {
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerProductosBajoStock() {
        $stmt = null;
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
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function verInventario($id_almacen) {
        $stmt = null;
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
            if (isset($stmt) && $stmt !== false) {
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
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    // transferneica pendiente
    public function contarTransferenciasPendientes() {
        $resultado = null;
        try {
            $query = "SELECT COUNT(*) as total FROM transferencias_stock WHERE estado = 'pendiente'";
            $resultado = $this->conn->query($query);
            
            if ($resultado) {
                $fila = $resultado->fetch_assoc();
                return (int) $fila['total'];
            }
            
            return 0;
        } catch (Exception $e) {
            // En caso de error, devolver 0
            return 0;
        } finally {
            if (isset($resultado) && $resultado !== false) {
                $resultado->free();
            }
        }
    }

    public function ajustarStock($id_producto, $id_almacen, $cantidad) {
        $stmt = null;
        $checkStmt = null;
        try {
            if ($cantidad < 0) {
                throw new Exception("La cantidad debe ser mayor o igual a 0.");
            }
    
            // Verificar si el producto ya existe en el almacén
            $checkStmt = $this->conn->prepare("SELECT id_stock, cantidad_disponible FROM stock_almacen WHERE id_producto = ? AND id_almacen = ? LIMIT 1");
            $checkStmt->bind_param("ii", $id_producto, $id_almacen);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();
            $exists = $result->num_rows > 0;
            $checkStmt->close();
            
            if ($exists) {
                // Si existe, actualizar
                $sql = "UPDATE stock_almacen SET cantidad_disponible = ? WHERE id_stock = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ii", $cantidad, $row['id_stock']);
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
        $stmt = null;
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
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerMovimientos($almacen = null, $producto = null, $tipo = null, $fecha_desde = null, $fecha_hasta = null, $limit = null, $offset = null) {
        $stmt = null;
        try {
            // Construir la consulta SQL base
            $sql = "SELECT 
                    ms.id_movimiento,
                    p.nombre AS producto,
                    ms.tipo_movimiento,
                    ms.cantidad,
                    ms.fecha_movimiento,
                    a_origen.nombre AS almacen_origen,
                    a_destino.nombre AS almacen_destino,
                    u.nombre AS usuario
                FROM movimientos_stock ms
                JOIN productos p ON ms.id_producto = p.id_producto
                LEFT JOIN almacenes a_origen ON ms.id_almacen_origen = a_origen.id_almacen
                LEFT JOIN almacenes a_destino ON ms.id_almacen_destino = a_destino.id_almacen
                LEFT JOIN usuarios u ON ms.id_usuario = u.id_usuario
                WHERE 1=1";
            
            // Añadir condiciones según los filtros
            $params = [];
            $types = "";
            
            if ($almacen) {
                $sql .= " AND (a_origen.nombre LIKE ? OR a_destino.nombre LIKE ?)";
                $almacenParam = "%$almacen%";
                $params[] = $almacenParam;
                $params[] = $almacenParam;
                $types .= "ss";
            }
            
            if ($producto) {
                $sql .= " AND p.nombre LIKE ?";
                $params[] = "%$producto%";
                $types .= "s";
            }
            
            if ($tipo) {
                $sql .= " AND ms.tipo_movimiento = ?";
                $params[] = $tipo;
                $types .= "s";
            }
            
            if ($fecha_desde) {
                $sql .= " AND DATE(ms.fecha_movimiento) >= ?";
                $params[] = $fecha_desde;
                $types .= "s";
            }
            
            if ($fecha_hasta) {
                $sql .= " AND DATE(ms.fecha_movimiento) <= ?";
                $params[] = $fecha_hasta;
                $types .= "s";
            }
            
            // Ordenar por fecha descendente
            $sql .= " ORDER BY ms.fecha_movimiento DESC";
            
            // Añadir límite y offset para paginación si se proporcionan
            if ($limit !== null) {
                $sql .= " LIMIT ?";
                $params[] = $limit;
                $types .= "i";
                
                if ($offset !== null) {
                    $sql .= " OFFSET ?";
                    $params[] = $offset;
                    $types .= "i";
                }
            }
            
            // Preparar y ejecutar la consulta
            $stmt = $this->conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error al obtener movimientos: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function contarMovimientosPorTipo() {
        $stmt = null;
        try {
            $tipos = ['entrada', 'salida', 'transferencia'];
            $resultado = [];
            
            foreach ($tipos as $tipo) {
                $stmt = $this->conn->prepare("
                    SELECT COUNT(*) as total 
                    FROM movimientos_stock 
                    WHERE tipo_movimiento = ?
                ");
                $stmt->bind_param("s", $tipo);
                $stmt->execute();
                $data = $stmt->get_result()->fetch_assoc();
                $resultado[$tipo] = $data['total'];
                $stmt->close();
                $stmt = null;
            }
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error al contar movimientos por tipo: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function almacenExiste($id_almacen) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT id_almacen FROM almacenes WHERE id_almacen =?");
            $stmt->bind_param("i", $id_almacen);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error al verificar existencia de almacén: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function generarReporte($almacen = null, $categoria = null, $proveedor = null) {
        $stmt = null;
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
            $result = $stmt->get_result();
            // Cerramos el statement después de obtener el resultado
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            error_log("Error al generar reporte: " . $e->getMessage());
            return [];
        }
        // Eliminamos el finally para evitar que se cierre el statement prematuramente
        // ya que el resultado se sigue utilizando en la vista
    }

    public function obtenerAlmacenOrigen($id_producto) {
        $stmt = null;
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
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function transferirStock($id_producto, $id_almacen_origen, $id_almacen_destino, $cantidad, $id_usuario) {
        $this->conn->begin_transaction();
        $stmt = null;
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
            $stmt = null;

            if ($stock_actual < $cantidad) {
                throw new Exception("Error: Stock insuficiente en el almacén de origen.");
            }

            $stmt = $this->conn->prepare("UPDATE stock_almacen SET cantidad_disponible = cantidad_disponible - ? WHERE id_producto = ? AND id_almacen = ?");
            $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen_origen);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en el almacén de origen.");
            }
            $stmt->close();
            $stmt = null;

            $stmt = $this->conn->prepare("INSERT INTO stock_almacen 
                                        (id_producto, id_almacen, cantidad_disponible) 
                                        VALUES (?, ?, ?) 
                                        ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible + ?");
            $stmt->bind_param("iiii", $id_producto, $id_almacen_destino, $cantidad, $cantidad);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al actualizar stock en el almacén de destino.");
            }
            $stmt->close();
            $stmt = null;

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
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerMovimientosRecientes($dias = 30) {
        $stmt = null;
        try {
            $fecha_limite = date('Y-m-d', strtotime("-$dias days"));
            
            $stmt = $this->conn->prepare("
                SELECT * FROM movimientos_stock 
                WHERE fecha_movimiento >= ? 
                ORDER BY fecha_movimiento DESC
            ");
            $stmt->bind_param("s", $fecha_limite);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener movimientos recientes: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }
}
?>



