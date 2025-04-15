<?php

class Productos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function validarProducto($id_producto) {
        try {
            $sql = "SELECT id_producto FROM productos WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error al validar producto: " . $e->getMessage());
            return false;
        }
    }
    public function obtenerProductos() {
        $stmt = null;
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

    // verificar si un producto existe
    public function nombreProductoExiste($nombreProducto) {
        try {
            $sql = "SELECT nombre FROM productos WHERE nombre = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $nombreProducto);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error al verificar nombre de producto: " . $e->getMessage());
            return false;
        }
    }

    



}