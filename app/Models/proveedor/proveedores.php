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

    public function obtenerProveedoresEliminados() {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT * FROM proveedores WHERE estado = 'eliminado'");
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener proveedores eliminados: " . $e->getMessage());
            return [];
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }

    // historial de proveedores

    public function obtenerHistorialProveedores($id_proveedor, $limit = 10, $offset = 0, $orden = 'nombre', $filtro_estado = 'activo') {
        $proveedor_info = [];
        $productos_proveedor = [];
        $stmt_proveedor = null;
        $stmt_productos = null;

        try {
            // 1. Obtener información del proveedor
            $stmt_proveedor = $this->conn->prepare("
                SELECT id_proveedor, nombre, contacto, telefono, email, direccion, estado 
                FROM proveedores 
                WHERE id_proveedor = ? AND estado != 'eliminado'
            ");
            $stmt_proveedor->bind_param("i", $id_proveedor);
            $stmt_proveedor->execute();
            $result_proveedor = $stmt_proveedor->get_result();
            
            if ($result_proveedor->num_rows > 0) {
                $proveedor_info = $result_proveedor->fetch_assoc();

                // 2. Obtener productos asociados al proveedor con paginación y ordenamiento
                $stmt_productos = $this->conn->prepare("
                    SELECT nombre, codigo, sku, descripcion, precio_venta 
                    FROM productos 
                    WHERE id_proveedor = ? AND estado = ?
                    ORDER BY $orden
                    LIMIT ? OFFSET ?
                ");
                $stmt_productos->bind_param("isii", $id_proveedor, $filtro_estado, $limit, $offset);
                $stmt_productos->execute();
                $result_productos = $stmt_productos->get_result();
                $productos_proveedor = $result_productos->fetch_all(MYSQLI_ASSOC);
            } else {
                return null; 
            }

            return [
                'proveedor' => $proveedor_info,
                'productos' => $productos_proveedor
            ];

        } catch (Exception $e) {
            error_log("Error al obtener historial del proveedor: " . $e->getMessage());
            return null;
        } finally {
            if ($stmt_proveedor !== null) {
                $stmt_proveedor->close();
            }
            if ($stmt_productos !== null) {
                $stmt_productos->close();
            }
        }
    }
        





    public function eliminarProveedor($id_proveedor) {
        $stmt = null;
        $checkStmt = null;
    
        try {
            // Verificar si el proveedor existe
            $checkStmt = $this->conn->prepare("SELECT id_proveedor FROM proveedores WHERE id_proveedor = ?");
            $checkStmt->bind_param("i", $id_proveedor);
            $checkStmt->execute();
    
            $result = $checkStmt->get_result();
            if ($result->num_rows > 0) {
                // Eliminar lógicamente: cambiar estado a 'eliminado'
                $stmt = $this->conn->prepare("UPDATE proveedores SET estado = 'eliminado' WHERE id_proveedor = ?");
                $stmt->bind_param("i", $id_proveedor);
                $stmt->execute();
                return $stmt->affected_rows > 0;
            } else {
                // No existe
                return false;
            }
        } catch (Exception $e) {
            error_log("Error al eliminar proveedor: " . $e->getMessage());
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


    public function restaurarProveedor($id_proveedor) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("UPDATE proveedores SET estado = 'activo' WHERE id_proveedor = ?");
            $stmt->bind_param("i", $id_proveedor);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error al restaurar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }
    


    public function obtenerProveedorPorId($id_proveedor) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
            $stmt->bind_param("i", $id_proveedor);
            $stmt->execute();
            $result = $stmt->get_result();
            $proveedor = $result->fetch_assoc();
            return $proveedor;
        } catch (Exception $e) {
            error_log("Error al obtener proveedor por ID: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }


    public function emailExisteExcepto($email, $id_proveedor) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT id_proveedor FROM proveedores WHERE email = ? AND id_proveedor != ?");
            $stmt->bind_param("si", $email, $id_proveedor);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error al verificar email: " . $e->getMessage());
            return false;
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }

    public function obtenerProveedoresConPaginacion($limit, $offset) {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT * FROM proveedores WHERE estado = 1 LIMIT ? OFFSET ?");
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $proveedores = $result->fetch_all(MYSQLI_ASSOC);
            return $proveedores;
        } catch (Exception $e) {
            error_log("Error al listar proveedores: " . $e->getMessage());
            return [];
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }
    public function contarTotalProveedores() {
        $stmt = null;
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM proveedores WHERE estado = 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $total = $result->fetch_row()[0];
            return $total;
        } catch (Exception $e) {
            error_log("Error al contar total de proveedores: " . $e->getMessage());
            return 0;
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }
        

    


}

// alter table para hacer activo y eliminado el proveedor para no eliminarlo en si
