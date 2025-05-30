<?php

class Proveedor
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // registrar neuvos proveedor
    public function registrarProveedor($nombre, $contacto, $direccion, $telefono, $email) {
        $stmt = null;
        try {

            // verificar que no existe el mismo proveedor con el mismo email
            $checkStmt  = $this->conn->prepare("SELECT id_proveedor FROM proveedores WHERE email = ?");
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result->num_rows > 0) {
                throw new Exception("Ya existe el proveedor con este email");
            }

            // si pasa este filtro normal todo ok

            $stmt = $this->conn->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre, $contacto, $telefono, $email, $direccion);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
    
        } catch (Exception $e) {
            error_log("Error al registrar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if ($checkStmt !== null) {
                $checkStmt->close();
            }
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }


    public function actualizarProveedor($id_proveedor, $nombre, $contacto, $direccion, $telefono, $email) {
        $stmt = null;

        try {
            $checkStmt = $this->conn->prepare("SELECT id_proveedor FROM proveedores WHERE email = ? AND id_proveedor != ?");
            $checkStmt->bind_param("si", $email, $id_proveedor);
            $checkStmt->execute();
            $result = $checkStmt->get_result();


            if ($result->num_rows > 0) {
                throw new Exception("Ya existe un proveedor con este email");
            }

            // si pasa este filtro normal todo ok

            $stmt = $this->conn->prepare("UPDATE proveedores SET nombre = ?, contacto = ?, telefono = ?, email = ?, direccion = ? WHERE id_proveedor = ?");
            $stmt->bind_param("sssssi", $nombre, $contacto, $telefono, $email, $direccion, $id_proveedor);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if ($checkStmt!== null) {
                $checkStmt->close();
            }
            if ($stmt!== null) {
                $stmt->close();
            }
        }
    }

    public function eliminarProveedor($id_proveedor) {
        $stmt = null;

        try {
            // verificar si existe el proveedor
            $checkStmt = $this->conn->prepare("SELECT id_proveedor FROM proveedores WHERE id_proveedor =?");
            $checkStmt->bind_param("i", $id_proveedor);
            $checkStmt->execute();

            $result = $checkStmt->get_result();
            if ($result->num_rows > 0) {
                $id_proveedor = $result->fetch_assoc()['id_proveedor'];
                $stmt = $this->conn->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
                $stmt->bind_param("i", $id_proveedor);
                $stmt->execute();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if ($checkStmt!== null) {
                $checkStmt->close();
            }
            if ($stmt!== null) {
                $stmt->close();
            }
        }
    }






}









