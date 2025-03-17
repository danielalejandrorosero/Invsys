<?php

// DASHBOARD DE LISTA PARA BUSCAR POR 

require_once '../config/cargarConfig.php';



nivelRequerido(1);

// Obtener parámetros de búsqueda
$nombreProducto       = isset($_GET['nombre']) ? trim($_GET['nombre']) : null;
$codigoProducto       = isset($_GET['codigo']) ? trim($_GET['codigo']) : null;
$skuProducto          = isset($_GET['sku']) ? trim($_GET['sku']) : null;
$CategoriaProducto    = isset($_GET['categoria']) ? trim($_GET['categoria']) : null;
$UnidadMedidaProducto = isset($_GET['unidad_medida']) ? trim($_GET['unidad_medida']) : null;

// Si no hay criterios de búsqueda, detener ejecución
if (empty($nombreProducto) && empty($codigoProducto) && empty($skuProducto) && empty($CategoriaProducto) && empty($UnidadMedidaProducto)) {
    die("Por favor, ingresa algún criterio de búsqueda para mostrar los productos.");
}

// Construcción de la consulta
$sql = "SELECT p.nombre, p.codigo, p.sku, p.descripcion, p.precio_compra, p.precio_venta, 
               p.stock_minimo, p.stock_maximo, c.nombre AS categoria, u.nombre AS unidad_medida
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN unidades_medida u ON p.id_unidad_medida = u.id_unidad";

$where  = [];
$params = [];
$types  = "";

// Aplicar filtros según los parámetros recibidos
if (!empty($nombreProducto)) {
    $where[] = "p.nombre LIKE ?";
    $params[] = "%$nombreProducto%";
    $types   .= "s";
}
if (!empty($codigoProducto)) {
    $where[] = "p.codigo LIKE ?";
    $params[] = "%$codigoProducto%";
    $types   .= "s";
}
if (!empty($skuProducto)) {
    $where[] = "p.sku LIKE ?";
    $params[] = "%$skuProducto%";
    $types   .= "s";
}
if (!empty($CategoriaProducto)) {
    $where[] = "c.nombre LIKE ?";
    $params[] = "%$CategoriaProducto%";
    $types   .= "s";
}
if (!empty($UnidadMedidaProducto)) {
    $where[] = "u.nombre LIKE ?";
    $params[] = "%$UnidadMedidaProducto%";
    $types   .= "s";
}

// Agregar condiciones a la consulta
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY p.nombre ASC";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log("Error en la preparación de la consulta: " . $conn->error);
    die("Error en la consulta.");
}

// Enlazar los parámetros dinámicamente (si existen)
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Mostrar resultados
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Nombre: " . $row['nombre'] . "\n";
        echo "Código: " . $row['codigo'] . "\n";
        echo "SKU: " . $row['sku'] . "\n";
        echo "Descripción: " . $row['descripcion'] . "\n";
        echo "Precio Compra: " . $row['precio_compra'] . "\n";
        echo "Precio Venta: " . $row['precio_venta'] . "\n";
        echo "Stock Mínimo: " . $row['stock_minimo'] . "\n";
        echo "Stock Máximo: " . $row['stock_maximo'] . "\n";
        echo "Categoría: " . $row['categoria'] . "\n";
        echo "Unidad de Medida: " . $row['unidad_medida'] . "\n";
        echo "-----------------------------\n";
    }
} else {
    echo "No se encontraron productos con esos criterios.\n";
}

$stmt->close();
?>
