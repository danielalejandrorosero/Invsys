<?php
require_once '../config/cargarConfig.php';
nivelRequerido(1);

$almacen = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
$producto = isset($_GET['producto']) ? trim($_GET['producto']) : null;
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : null;

$sql = "SELECT 
            ms.id_movimiento, 
            p.nombre AS producto, 
            ms.tipo_movimiento, 
            ms.cantidad, 
            ms.fecha_movimiento,
            a_origen.nombre AS almacen_origen,
            a_destino.nombre AS almacen_destino,
            COALESCE(u.nombre, 'Sin usuario') AS usuario
        FROM movimientos_stock ms
        JOIN productos p ON ms.id_producto = p.id_producto
        LEFT JOIN almacenes a_origen ON ms.id_almacen_origen = a_origen.id_almacen
        LEFT JOIN almacenes a_destino ON ms.id_almacen_destino = a_destino.id_almacen
        LEFT JOIN usuarios u ON ms.id_usuario = u.id_usuario";

$where = [];
$params = [];
$types = "";

if (!empty($almacen)) {
    $where[] = "(a_origen.nombre LIKE ? OR a_destino.nombre LIKE ?)";
    $params[] = "%$almacen%";
    $params[] = "%$almacen%";
    $types   .= "ss";
}

if (!empty($producto)) {
    $where[] = "p.nombre LIKE ?";
    $params[] = "%$producto%";
    $types   .= "s";
}

if (!empty($tipo)) {
    $where[] = "ms.tipo_movimiento LIKE ?";
    $params[] = "%$tipo%";
    $types   .= "s";
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY ms.fecha_movimiento DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

echo "<table>";
echo "<thead><tr><th>ID</th><th>Producto</th><th>Tipo</th><th>Cantidad</th><th>Fecha</th><th>Almacén Origen</th><th>Almacén Destino</th><th>Usuario</th></tr></thead>";
echo "<tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id_movimiento']}</td>";
    echo "<td>{$row['producto']}</td>";
    echo "<td>{$row['tipo_movimiento']}</td>";
    echo "<td>{$row['cantidad']}</td>";
    echo "<td>{$row['fecha_movimiento']}</td>";
    echo "<td>{$row['almacen_origen']}</td>";
    echo "<td>{$row['almacen_destino']}</td>";
    echo "<td>{$row['usuario']}</td>";
    echo "</tr>";
}

echo "</tbody></table>";

$stmt->close();
?>
