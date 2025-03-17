<?php

require_once '../config/cargarConfig.php';
nivelRequerido(1);

$filters = [];
$params = [];

if (isset($_GET['producto']) && !empty(trim($_GET['producto']))) {
    $filters[] = "p.nombre LIKE ?";
    $params[] = "%" . trim($_GET['producto']) . "%";
}

if (isset($_GET['almacen']) && !empty(trim($_GET['almacen']))) {
    $filters[] = "(a_origen.nombre LIKE ? OR a_destino.nombre LIKE ?)";
    $almacen_filter = "%" . trim($_GET['almacen']) . "%";
    $params[] = $almacen_filter;
    $params[] = $almacen_filter;
}

if (isset($_GET['tipo']) && !empty(trim($_GET['tipo']))) {
    $filters[] = "ms.tipo_movimiento = ?";
    $params[] = trim($_GET['tipo']);
}

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

// Si hay filtros, se agrega la cláusula WHERE
if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

$sql .= " ORDER BY ms.fecha_movimiento DESC";

$stmt = $conn->prepare($sql);

// Vincular parámetros si existen
if (count($params) > 0) {
    $types = str_repeat('s', count($params)); // Asumimos todos los parámetros tipo string
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$movimientos = [];
while ($row = $result->fetch_assoc()) {
    $movimientos[] = $row;
}

// Se envía la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($movimientos);
