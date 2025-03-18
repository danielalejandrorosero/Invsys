<?php
require_once '../config/cargarConfig.php';

nivelRequerido(1);

$almacen   = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : null;
$proveedor = isset($_GET['proveedor']) ? trim($_GET['proveedor']) : null;

$sql = "SELECT 
            p.id_producto,
            p.nombre AS producto,
            a.nombre AS almacen,
            sa.cantidad_disponible AS cantidad,
            p.stock_minimo,
            p.stock_maximo,
            c.nombre AS categoria,
            pr.nombre AS proveedor
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN proveedores pr ON p.id_proveedor = pr.id_proveedor";

$where  = [];
$params = [];
$types  = "";

if (!empty($almacen)) {
    $where[] = "a.nombre LIKE ?";
    $params[] = "%{$almacen}%";  
    $types   .= "s";
}

if (!empty($categoria)) {
    $where[] = "c.nombre LIKE ?";
    $params[] = "%{$categoria}%";
    $types   .= "s";
}

if (!empty($proveedor)) {
    $where[] = "pr.nombre LIKE ?";
    $params[] = "%{$proveedor}%";
    $types   .= "s";
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY p.nombre ASC"; 

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Error en la consulta SQL: " . $conn->error);
    exit("Hubo un problema al procesar la consulta.");
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

echo "<table>";
echo "<thead><tr><th>Producto</th><th>Categoría</th><th>Proveedor</th><th>Almacén</th><th>Cantidad</th><th>Stock Mínimo</th><th>Stock Máximo</th></tr></thead>";
echo "<tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['producto']}</td>";
    echo "<td>{$row['categoria']}</td>";
    echo "<td>{$row['proveedor']}</td>";
    echo "<td>{$row['almacen']}</td>";
    echo "<td>{$row['cantidad']}</td>";
    echo "<td>{$row['stock_minimo']}</td>";
    echo "<td>{$row['stock_maximo']}</td>";
    echo "</tr>";
}

echo "</tbody></table>";

$stmt->close();
?>
