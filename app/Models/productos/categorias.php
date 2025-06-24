<?php

class Categorias {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function crearCategoria($nombre, $descripcion) {
        $stmt = null;
        try {
            $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $nombre, $descripcion);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error al crear categoría: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerCategorias() {
        $stmt = null;
        try {
            $stmt = $this->conn->query("SELECT id_categoria, nombre, descripcion FROM categorias ORDER BY nombre");
            return $stmt->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener categorías: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerCategoriaPorId($id_categoria) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error al obtener categoría por ID: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function editarCategoria($id_categoria, $nombre, $descripcion) {
        $stmt = null;
        try {
            $sql = "UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $descripcion, $id_categoria);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error al editar categoría: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function eliminarCategoria($id_categoria) {
        $stmt = null;
        try {
            $sql = "DELETE FROM categorias WHERE id_categoria = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error al eliminar categoría: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function existeCategoria($nombre) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM categorias WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();
            return $row['total'] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar existencia de categoría: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function categoriaEnUso($id_categoria) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM productos WHERE id_categoria = ?");
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();
            return $row['total'] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar uso de categoría: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }
}
?>