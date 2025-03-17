<?php
require_once '../config/cargarConfig.php';

nivelRequerido(1);

// Construcción de filtros
$filters = [];
$params = [];

if (isset($_GET['almacen']) && !empty($_GET['almacen'])) {
    $filters[] = "a.id_almacen = ?";
    $params[] = $_GET['almacen'];
}

if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $filters[] = "p.id_categoria = ?";
    $params[] = $_GET['categoria'];
}

if (isset($_GET['proveedor']) && !empty($_GET['proveedor'])) {
    $filters[] = "p.id_proveedor = ?";
    $params[] = $_GET['proveedor'];
}

$sql = "SELECT 
            p.id_producto,
            p.nombre AS producto,
            a.nombre AS almacen,
            sa.cantidad_disponible AS cantidad,
            p.stock_minimo,
            p.stock_maximo
        FROM stock_almacen sa
        JOIN productos p ON sa.id_producto = p.id_producto
        JOIN almacenes a ON sa.id_almacen = a.id_almacen";

if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

$sql .= " ORDER BY p.nombre, a.nombre";

$stmt = $conn->prepare($sql);

if (count($params) > 0) {
    $types = str_repeat('i', count($params)); 
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$reporte = [];
while ($row = $result->fetch_assoc()) {
    if ($row['cantidad'] < $row['stock_minimo']) {
        $estado = "Bajo";
    } elseif ($row['cantidad'] > $row['stock_maximo']) {
        $estado = "Alto";
    } else {
        $estado = "Normal";
    }
    $row['estado'] = $estado;
    $reporte[] = $row;
}

// Procesar los datos sin devolver HTML ni JSON
foreach ($reporte as $registro) {
    echo "Producto: " . $registro['producto'] . PHP_EOL;
    echo "Almacén: " . $registro['almacen'] . PHP_EOL;
    echo "Cantidad Disponible: " . $registro['cantidad'] . PHP_EOL;
    echo "Stock Mínimo: " . $registro['stock_minimo'] . PHP_EOL;
    echo "Stock Máximo: " . $registro['stock_maximo'] . PHP_EOL;
    echo "Estado: " . $registro['estado'] . PHP_EOL;
    echo "-------------------------" . PHP_EOL;
}
?>
