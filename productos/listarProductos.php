<?php

// mostrar errores


require_once '../config/cargarConfig.php';

nivelRequerido(1);

// Consulta para obtener todos los productos con el nombre de la categoría y de la unidad de medida
$sql = "SELECT p.nombre, p.codigo, p.sku, p.descripcion, p.precio_compra, p.precio_venta, p.stock_minimo, p.stock_maximo, c.nombre
AS categoria, u.nombre 
AS unidad_medida
FROM productos p
JOIN categorias c ON p.id_categoria = c.id_categoria
JOIN unidades_medida u ON p.id_unidad_medida = u.id_unidad;
";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0  '>";
    echo "<tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>SKU</th>
            <th>Descripción</th>
            <th>Precio Compra</th>
            <th>Precio Venta</th>
            <th>Stock Mínimo</th>
            <th>Stock Máximo</th>
            <th>Categoría</th>
            <th>Unidad de Medida</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sku']) . "</td>";
        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
        echo "<td>" . htmlspecialchars($row['precio_compra']) . "</td>";
        echo "<td>" . htmlspecialchars($row['precio_venta']) . "</td>";
        echo "<td>" . htmlspecialchars($row['stock_minimo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['stock_maximo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
        echo "<td>" . htmlspecialchars($row['unidad_medida']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay productos para mostrar.";
}
?>