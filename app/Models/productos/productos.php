<?php

class productos
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validarProducto($id_producto)
    {
        try {
            $sql = "SELECT id_producto FROM productos WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log("Error al validar producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerProductos()
    {
        $result = null;
        try {
            $sql =
                "SELECT id_producto, nombre FROM productos WHERE estado = 'activo'";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            return [];
        } finally {
            if (isset($result) && $result !== false) {
                $result->free();
            }
        }
    }

    public function eliminarProducto($id_producto)
    {
        try {
            $sql =
                "UPDATE productos SET estado = 'eliminado' WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $resultado = $stmt->execute();

            // Verificar errores específicos
            if (!$resultado) {
                error_log(
                    "Error al eliminar lógicamente el producto: " . $stmt->error
                );
                return false;
            }

            // Verificar si realmente se actualizó el producto
            if ($stmt->affected_rows <= 0) {
                error_log(
                    "No se actualizó el estado del producto: " . $stmt->error
                );
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log(
                "Error al eliminar lógicamente el producto: " . $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    // NOTA: Este método es duplicado de validarProducto(), considerar unificar en el futuro
    // COMENTADO: No usar este método, utilizar validarProducto() en su lugar
    /*
    public function nombreProductoExiste($id_producto)
    {
        try {
            $sql = "SELECT id_producto FROM productos WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar producto por ID: " . $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }
    */

    public function agregarProducto(
        $nombre,
        $codigo,
        $sku,
        $descripcion,
        $precio_compra,
        $precio_venta,
        $id_unidad_medida,
        $stock_minimo,
        $stock_maximo,
        $id_categoria,
        $id_proveedor
    ) {
        try {
            $sql = "INSERT INTO productos
                    (nombre, codigo, sku, descripcion, precio_compra, precio_venta, id_unidad_medida, stock_minimo, stock_maximo, id_categoria, id_proveedor)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssddiiiii",
                $nombre,
                $codigo,
                $sku,
                $descripcion,
                $precio_compra,
                $precio_venta,
                $id_unidad_medida,
                $stock_minimo,
                $stock_maximo,
                $id_categoria,
                $id_proveedor
            );
            $resultado = $stmt->execute();
            return $resultado;
        } catch (Exception $e) {
            error_log("Error al agregar producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function categoriaExiste($id_categoria)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id_categoria FROM categorias WHERE id_categoria = ?"
            );
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;

            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar existencia de categoría: " .
                    $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function proveedorExiste($id_proveedor)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id_proveedor FROM proveedores WHERE id_proveedor = ?"
            );
            $stmt->bind_param("i", $id_proveedor);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar existencia de proveedor: " .
                    $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function unidadMedidaExiste($id_unidad_medida)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id_unidad FROM unidades_medida WHERE id_unidad = ?"
            );
            $stmt->bind_param("i", $id_unidad_medida);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar existencia de unidad de medida: " .
                    $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function skuExiste($sku)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT sku FROM productos WHERE sku = ?"
            );
            $stmt->bind_param("s", $sku);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar existencia de SKU: " . $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function codigoExiste($codigo)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT codigo FROM productos WHERE codigo = ?"
            );
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $stmt->store_result();
            $existe = $stmt->num_rows > 0;
            return $existe;
        } catch (Exception $e) {
            error_log(
                "Error al verificar existencia de código: " . $e->getMessage()
            );
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    // Nuevos métodos para listas dinámicas
    public function obtenerCategorias()
    {
        $result = null;
        try {
            $sql = "SELECT id_categoria, nombre FROM categorias";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener categorías: " . $e->getMessage());
            return [];
        } finally {
            if (isset($result) && $result !== false) {
                $result->free();
            }
        }
    }

    public function obtenerProveedores()
    {
        $result = null;
        try {
            $sql = "SELECT id_proveedor, nombre FROM proveedores";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener proveedores: " . $e->getMessage());
            return [];
        } finally {
            if (isset($result) && $result !== false) {
                $result->free();
            }
        }
    }

    public function obtenerUnidadesMedida()
    {
        $result = null;
        try {
            $sql = "SELECT id_unidad, nombre FROM unidades_medida";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log(
                "Error al obtener unidades de medida: " . $e->getMessage()
            );
            return [];
        } finally {
            if (isset($result) && $result !== false) {
                $result->free();
            }
        }
    }

    // buscar productos
    public function buscarProductos(
        $nombre = null,
        $codigo = null,
        $sku = null,
        $categoria = null,
        $unidad_medida = null
    ) {
        $stmt = null; // Inicializar $stmt para prevenir variable indefinida

        try {
            $sql = "SELECT p.nombre, p.codigo, p.sku, p.descripcion, p.precio_compra, p.precio_venta,
                           p.stock_minimo, p.stock_maximo, c.nombre AS categoria, u.nombre AS unidad_medida
                    FROM productos p
                    JOIN categorias c ON p.id_categoria = c.id_categoria
                    JOIN unidades_medida u ON p.id_unidad_medida = u.id_unidad";

            // Primero agregamos el filtro de estado activo
            $conditions = ["p.estado = 'activo'"];
            $params = [];
            $types = "";

            if (!empty($nombre)) {
                $conditions[] = "p.nombre LIKE ?";
                $params[] = "%$nombre%";
                $types .= "s";
            }

            if (!empty($codigo)) {
                $conditions[] = "p.codigo LIKE ?";
                $params[] = "%$codigo%";
                $types .= "s";
            }

            if (!empty($sku)) {
                $conditions[] = "p.sku LIKE ?";
                $params[] = "%$sku%";
                $types .= "s";
            }

            if (!empty($categoria)) {
                $conditions[] = "c.nombre LIKE ?";
                $params[] = "%$categoria%";
                $types .= "s";
            }

            if (!empty($unidad_medida)) {
                $conditions[] = "u.nombre LIKE ?";
                $params[] = "%$unidad_medida%";
                $types .= "s";
            }

            // Siempre añadimos las condiciones porque al menos tenemos p.estado = 'activo'
            $sql .= " WHERE " . implode(" AND ", $conditions);
            $sql .= " ORDER BY p.nombre ASC";

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                throw new Exception(
                    "Error al preparar la consulta: " . $this->conn->error
                );
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $resultado = $stmt->get_result();
            $productos = $resultado->fetch_all(MYSQLI_ASSOC);

            return $productos;
        } catch (Exception $e) {
            error_log("Error al buscar productos: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerProductoPorId($id_producto)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM productos WHERE id_producto = ?"
            );
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $producto = $resultado->fetch_assoc();

            return $producto;
        } catch (Exception $e) {
            error_log("Error al obtener producto por ID: " . $e->getMessage());
            return null;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function codigoExisteExcepto($codigo, $id_producto)
    {
        try {
            $sql =
                "SELECT COUNT(*) as count FROM productos WHERE codigo = ? AND id_producto != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $codigo, $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["count"] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar código: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function skuExisteExcepto($sku, $id_producto)
    {
        try {
            $sql =
                "SELECT COUNT(*) as count FROM productos WHERE sku = ? AND id_producto != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $sku, $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["count"] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar SKU: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    // consulta para listar producto
    public function obtenerProductosConPaginacion($limit, $offset)
    {
        try {
            $sql = "SELECT
                p.*,
                c.nombre AS categoria_nombre,
                pr.nombre AS proveedor_nombre,
                um.nombre AS unidad_medida_nombre,
                COALESCE(ip.nombre_imagen, 'default.png') AS imagen_destacada
            FROM productos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
            LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad
            LEFT JOIN (
                SELECT id_producto, MIN(nombre_imagen) AS nombre_imagen
                FROM imagenes_productos
                GROUP BY id_producto
            ) ip ON p.id_producto = ip.id_producto
            WHERE p.estado = 'activo'
            ORDER BY p.id_producto ASC
            LIMIT ? OFFSET ?;";

            $resultado = $this->conn->prepare($sql);
            $resultado->bind_param("ii", $limit, $offset);
            $resultado->execute();
            $productos = $resultado->get_result()->fetch_all(MYSQLI_ASSOC);
            return $productos;
        } catch (Exception $e) {
            error_log(
                "Error al obtener productos: " . $e->getMessage()
            );
            return [];
        } finally {
            if (isset($resultado) && $resultado !== false) {
                $resultado->close();
            }
        }
    }

    // NOTA: Este método es similar a validarProducto() y nombreProductoExiste(), considerar unificar
    // COMENTADO: No usar este método, utilizar validarProducto() en su lugar
    /*
    public function productoExiste($id_producto) {
        try {
            $stmt = $this->conn->prepare("SELECT id_producto FROM productos WHERE id_producto =?");
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error al verificar existencia de producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }
    */

    // NOTA: Este método es duplicado de contarTotalProductos(), considerar unificar
    // COMENTADO: No usar este método, utilizar contarTotalProductos() en su lugar
    /*
    public function contarProductos()
    {
        $resultado = null;
        try {
            $sql =
                "SELECT COUNT(*) AS total FROM productos WHERE estado = 'activo'";
            $resultado = $this->conn->query($sql);
            $row = $resultado->fetch_assoc();
            return $row["total"];
        } catch (Exception $e) {
            error_log("Error al contar productos: " . $e->getMessage());
            return 0;
        } finally {
            if (isset($resultado) && $resultado !== false) {
                $resultado->free();
            }
        }
    }
    */

    // NOTA: Este método es duplicado de contarProductos(), considerar unificar
    public function contarTotalProductos()
    {
        try {
            $sql =
                "SELECT COUNT(*) as count FROM productos WHERE estado = 'activo' ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row["count"];
        } catch (Exception $e) {
            error_log("Error al contar productos: " . $e->getMessage());
            return 0;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function restaurarProducto($id_producto)
    {
        try {
            $sql =
                "UPDATE productos SET estado = 'activo' WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $resultado = $stmt->execute();

            if (!$resultado || $stmt->affected_rows <= 0) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log("Error al restaurar el producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
    }

    public function obtenerProductosEliminados()
    {
        $resultado = null;
        try {
            $sql = "SELECT p.*,
                        c.nombre as categoria_nombre,
                        pr.nombre as proveedor_nombre,
                        um.nombre as unidad_medida_nombre
                        FROM productos p
                        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor
                        LEFT JOIN unidades_medida um ON p.id_unidad_medida = um.id_unidad
                        WHERE p.estado = 'eliminado'
                        ORDER BY p.id_producto DESC";
            $resultado = $this->conn->prepare($sql);
            $resultado->execute();

            $result = $resultado->get_result();
            $productos = $result->fetch_all(MYSQLI_ASSOC);

            return $productos;
        } catch (Exception $e) {
            error_log(
                "Error al obtener productos eliminados: " . $e->getMessage()
            );
            return [];
        } finally {
            if (isset($resultado) && $resultado !== false) {
                $resultado->close();
            }
        }
    }

    public function actualizarProducto(
        $id_producto,
        $nombre,
        $codigo,
        $sku,
        $descripcion,
        $precio_compra,
        $precio_venta,
        $id_unidad_medida,
        $stock_minimo,
        $stock_maximo,
        $id_categoria,
        $id_proveedor,
        $imagen = null
    ) {
        try {
            $sql =
                "UPDATE productos SET nombre = ?, codigo = ?, sku = ?, descripcion = ?, precio_compra = ?, precio_venta = ?, id_unidad_medida = ?, stock_minimo = ?, stock_maximo = ?, id_categoria = ?, id_proveedor = ? WHERE id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param(
                "ssssddiiiiii",
                $nombre,
                $codigo,
                $sku,
                $descripcion,
                $precio_compra,
                $precio_venta,
                $id_unidad_medida,
                $stock_minimo,
                $stock_maximo,
                $id_categoria,
                $id_proveedor,
                $id_producto
            );
            $stmt->execute();
            $resultado = $stmt->affected_rows;
        } catch (Exception $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            $resultado = false;
        } finally {
            if (isset($stmt) && $stmt !== false) {
                $stmt->close();
            }
        }
        return $resultado;
    }
}
?>
