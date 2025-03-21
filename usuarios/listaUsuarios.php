<?php
require_once '../config/cargarConfig.php';

nivelRequerido(1);

$error = [];

session_start();

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../frontend/listaUsuarios.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Lista de Usuarios</h1>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
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
            echo "<p>No hay usuarios para mostrar.</p>";
        }
        ?>
    </div>
</body>
</html>