<?php

require_once '../config/cargarConfig.php';

//nivelRequerido(1);





// dasboard

// Consulta para obtener todos los usuarios con el nombre de la tabla y el nivel de acceso
$sql = "SELECT u.id_usuario, u.nombre, u.email, u.nivel_usuario, g.nombre_grupo AS grupo
FROM usuarios u
JOIN grupos g ON u.nivel_usuario = g.id_grupo";
$result = $conn->query($sql);


if (!$result) {
    throw new Exception("Error al obtener los usuarios.");
}

if ($result->num_rows === 0) {
    throw new Exception("No se encontraron usuarios.");
}

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0  '>";
    echo "<tr>
            <th>Nombre</th>
            <th>Email</th>

            <th>Grupo</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['grupo']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay usuarios para mostrar.";
}

?>