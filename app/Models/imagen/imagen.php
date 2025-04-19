<?php
class Imagen
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Subir imagen genérica para usuarios o productos
    public function subirImagen($tipo, $id, $nombreArchivoSeguro, $destino)
    {
        try {
            if ($tipo === "producto") {
                // Verificar si ya existe una imagen para el producto
                $sqlCheck = "SELECT COUNT(*) AS total FROM imagenes_productos WHERE id_producto = ?";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->bind_param("i", $id);
                $stmtCheck->execute();
                $result = $stmtCheck->get_result();
                $row = $result->fetch_assoc();
                $stmtCheck->close();

                if ($row['total'] > 0) {
                    // Si ya existe, actualizamos la imagen
                    $sql = "UPDATE imagenes_productos SET nombre_imagen = ?, ruta_imagen = ? WHERE id_producto = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("ssi", $nombreArchivoSeguro, $destino, $id);
                } else {
                    // Si no existe, insertamos una nueva imagen
                    $sql = "INSERT INTO imagenes_productos (id_producto, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("iss", $id, $nombreArchivoSeguro, $destino);
                }
            } elseif ($tipo === "usuario") {
                // Similar lógica para usuarios
                $sqlCheck = "SELECT COUNT(*) AS total FROM imagenes_usuarios WHERE id_usuario = ?";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->bind_param("i", $id);
                $stmtCheck->execute();
                $result = $stmtCheck->get_result();
                $row = $result->fetch_assoc();
                $stmtCheck->close();

                if ($row['total'] > 0) {
                    $sql = "UPDATE imagenes_usuarios SET nombre_imagen = ?, ruta_imagen = ? WHERE id_usuario = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("ssi", $nombreArchivoSeguro, $destino, $id);
                } else {
                    $sql = "INSERT INTO imagenes_usuarios (id_usuario, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("iss", $id, $nombreArchivoSeguro, $destino);
                }
            } else {
                throw new Exception("Tipo no válido: $tipo");
            }

            if ($stmt->execute()) {
                error_log("Imagen actualizada/insertada correctamente en la base de datos para $tipo con ID $id.");
                return true;
            } else {
                error_log("Error al ejecutar la consulta: " . $stmt->error);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error en subirImagen: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
}