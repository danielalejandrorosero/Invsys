<?php

class Proveedor
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function obtenerProveedores()
    {
        try {
            $sql = "SELECT * FROM proveedores";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $stmt->close();
        }
    }
}
