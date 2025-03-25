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
        $stmt = $this->conn->prepare("SELECT p.nombre AS producto, sa.cantidad_disponible AS cantidad 
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
        return $inventario;
    }

    public function obtenerAlmacenes() {
        $stmt = $this->conn->query("SELECT id_almacen, nombre FROM almacenes");
        return $stmt->fetch_all(MYSQLI_ASSOC);
    }
    

    

    public function ajustarStock($id_producto, $id_almacen, $cantidad) {
        try {
            if ($cantidad < 0) {
                throw new Exception("La cantidad debe ser mayor o igual a 0.");
            }

            $sql = "UPDATE stock_almacen SET cantidad_disponible = ? WHERE id_producto = ? AND id_almacen = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $cantidad, $id_producto, $id_almacen);
            $resultado = $stmt->execute();
            // No cerrar el stmt aquí, se hará en el finally
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




    public function obtenerProductos() {
        try {
            $stmt = $this->conn->query("SELECT id_producto, nombre FROM productos");
            return $stmt->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function obtenerAlmacenesConProducto($id_producto) {
        $stmt = $this->conn->prepare("SELECT a.id_almacen, a.nombre 
                                      FROM stock_almacen sa
                                      JOIN almacenes a ON sa.id_almacen = a.id_almacen
                                      WHERE sa.id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $almacenes = [];
        while ($row = $result->fetch_assoc()) {
            $almacenes[] = $row;
        }
        
        $stmt->close();
        return $almacenes;
    }

}









?>