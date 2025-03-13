<?php
require_once '../config/cargarConfig.php';
session_start();

$error = [];

// Requerir nivel de acceso (admin)
nivelRequerido(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizarProducto'])) {

    // Campos requeridos para actualizar un producto
    $camposRequeridos = [
        'id_producto',
        'nombre',
        'codigo',
        'sku',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'stock_maximo',
        'id_categoria',
        'id_unidad_medida'
    ];
    validarCampos($camposRequeridos);

    // Obtener ID del producto a editar 
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

    // Sanitizar y validar datos
    $nombre          = htmlspecialchars(trim($_POST['nombre']));
    $codigo          = htmlspecialchars(trim($_POST['codigo']));
    $sku             = htmlspecialchars(trim($_POST['sku']));
    $descripcion     = htmlspecialchars(trim($_POST['descripcion']));
    $precio_compra   = filter_var(str_replace(['$', ','], '', $_POST['precio_compra']), FILTER_VALIDATE_FLOAT);
    $precio_venta    = filter_var(str_replace(['$', ','], '', $_POST['precio_venta']), FILTER_VALIDATE_FLOAT);
    $id_unidad_medida= (int) $_POST['id_unidad_medida'];
    $stock_minimo    = (int) $_POST['stock_minimo'];
    $stock_maximo    = (int) $_POST['stock_maximo'];
    $id_categoria    = (int) $_POST['id_categoria'];

    // Validar precios
    if (!$precio_compra || $precio_compra <= 0 || !$precio_venta || $precio_venta <= 0) {
        $error[] = "El precio de compra y venta deben ser mayores a 0.";
    }

    // Validar stock
    if ($stock_maximo < $stock_minimo) {
        $error[] = "El stock máximo debe ser mayor o igual al stock mínimo.";
    }

    // Validar existencia de categoría
    $stmt = $conn->prepare("SELECT id_categoria FROM categorias WHERE id_categoria = ?");
    $stmt->bind_param("i", $id_categoria);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        $error[] = "La categoría no existe.";
    }
    $stmt->close();

    // Validar código único (excluyendo el producto actual)
    $stmt = $conn->prepare("SELECT codigo FROM productos WHERE codigo = ? AND id_producto != ?");
    $stmt->bind_param("si", $codigo, $id_producto);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error[] = "El código ya existe.";
    }
    $stmt->close();

    // Validar existencia de unidad de medida
    $stmt = $conn->prepare("SELECT id_unidad FROM unidades_medida WHERE id_unidad = ?");
    $stmt->bind_param("i", $id_unidad_medida);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        $error[] = "La unidad de medida no existe.";
    }
    $stmt->close();

    // Validar SKU único (excluyendo el producto actual)
    $stmt = $conn->prepare("SELECT sku FROM productos WHERE sku = ? AND id_producto != ?");
    $stmt->bind_param("si", $sku, $id_producto);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error[] = "El SKU ya existe.";
    }
    $stmt->close();
    
    // Si no hay errores, proceder a editar el producto
    if (empty($error)) {
        $sql = "UPDATE productos 
                SET nombre = ?, 
                    codigo = ?, 
                    sku = ?, 
                    descripcion = ?, 
                    precio_compra = ?, 
                    precio_venta = ?, 
                    id_unidad_medida = ?, 
                    stock_minimo = ?, 
                    stock_maximo = ?, 
                    id_categoria = ? 
                WHERE id_producto = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // 4 s (nombre, codigo, sku, descripcion), 2 d (precio_compra, precio_venta), 5 i (id_unidad_medida, stock_minimo, stock_maximo, id_categoria, id_producto)
            $stmt->bind_param("ssssddiiiii",
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
                $id_producto
            );
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "Producto editado exitosamente!";
            } else {
                $error[] = "No se pudo editar el producto o no se realizaron cambios.";
            }
            $stmt->close();
        } else {
            $error[] = "Error en la preparación de la consulta: " . $conn->error;
        }
    }

    // Mostrar errores si los hay
    if (!empty($error)) {
        foreach ($error as $err) {
            echo "<p style='color:red;'>$err</p>";
        }
    }
}
?>
