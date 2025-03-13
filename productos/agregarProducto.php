<?php



require_once '../config/cargarConfig.php';



$error = [];

date_default_timezone_set('America/Bogota');

nivelRequerido(1);

// Verificar si el usuario es administrador


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregarProducto'])) {
    $camposRequeridos = ['nombre', 'codigo', 'sku', 'descripcion', 'precio_compra', 'precio_venta', 'stock_minimo', 'stock_maximo', 'id_categoria', 'id_unidad_medida'];
    validarCampos($camposRequeridos);

    if (empty($error)) {
        // Sanitizar y validar datos
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $codigo = htmlspecialchars(trim($_POST['codigo']));
        $sku = htmlspecialchars(trim($_POST['sku']));
        $descripcion = htmlspecialchars(trim($_POST['descripcion']));
        $precio_compra = filter_var(str_replace(['$', ','], '', $_POST['precio_compra']), FILTER_VALIDATE_FLOAT);
        $precio_venta = filter_var(str_replace(['$', ','], '', $_POST['precio_venta']), FILTER_VALIDATE_FLOAT);
        $id_unidad_medida = (int) $_POST['id_unidad_medida'];
        $stock_minimo = (int) $_POST['stock_minimo'];
        $stock_maximo = (int) $_POST['stock_maximo'];
        $id_categoria = (int) $_POST['id_categoria'];

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

        // codigo debe 
        $stmt = $conn->prepare("SELECT codigo FROM productos WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
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

        // Validar SKU único
        $stmt = $conn->prepare("SELECT sku FROM productos WHERE sku = ?");
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error[] = "El SKU ya existe.";
        }
        $stmt->close();

        // Si no hay errores, insertar producto
        if (empty($error)) {
            $sql = "INSERT INTO productos 
            (nombre,
            codigo, 
            sku, 
            descripcion, 
            precio_compra, 
            precio_venta, 
            id_unidad_medida, 
            stock_minimo, 
            stock_maximo, 
            id_categoria) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssddiiii",
            $nombre, 
            $codigo, 
            $sku, 
            $descripcion, 
            $precio_compra, 
            $precio_venta, 
            $id_unidad_medida, 
            $stock_minimo, 
            $stock_maximo, 
            $id_categoria);

            if ($stmt->execute()) {
                echo "Producto agregado correctamente.";
            } else {
                $error[] = "Error al agregar el producto.";
            }
            $stmt->close();
        }
    }
}



// Mostrar errores
if (!empty($error)) {
    foreach ($error as $err) {
        echo "$err<br>";
    }
}

?>
