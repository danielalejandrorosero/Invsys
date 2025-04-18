<?php
class Imagen {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Subir imagen genérica para usuarios o productos
    public function subirImagen($tipo, $id, $nombreArchivoSeguro, $destino) {
        try {
            $tabla = $tipo === 'usuario' ? 'imagenes_usuarios' : 'imagenes_productos';
            $columnaId = $tipo === 'usuario' ? 'id_usuario' : 'id_producto';

            $sql = "INSERT INTO $tabla ($columnaId, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $id, $nombreArchivoSeguro, $destino);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al subir imagen ($tipo): " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
}
?>