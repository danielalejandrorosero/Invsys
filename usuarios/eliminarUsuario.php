<?php

require_once '../config/cargarConfig.php';

$error = [];

nivelRequerido(1);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarUsuario'])) {
    if (empty($error)) {
        $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        $id_usuario_sesion = $_SESSION['id_usuario'] ?? 0;
        $nivel_sesion = $_SESSION['nivel_usuario'] ?? 0;

        // Verificar si hay más de un administrador en la base de datos
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE nivel_usuario = 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_admins = $row['total'];

        // Obtener el nivel del usuario a eliminar
        $sql = "SELECT nivel_usuario FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($nivel_usuario_eliminar);
        $stmt->fetch();
        $stmt->close();

        if ($nivel_usuario_eliminar == 1 && $total_admins <= 1) {
            $error[] = "No puedes eliminar el último administrador.";
        } else {
            if ($nivel_sesion == 1) {
                $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $id_usuario);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        $mensaje = "Usuario eliminado exitosamente!";
                        header("Location: listaUsuarios.php");
                        exit();
                    } else {
                        $error[] = "No se encontró el usuario o error al eliminar.";
                    }
                    $stmt->close();
                } else {
                    error_log("Error en la consulta SQL: " . $conn->error);
                    $error[] = "Error interno, intenta más tarde.";
                }
            } else {
                $error[] = "No tienes permisos para eliminar este usuario.";
            }
        }
    }
}

// obtener lista de usuarios
$sql = "SELECT id_usuario, nombreUsuario FROM usuarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="../frontend/eliminarUsuario.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Eliminar Usuario</h1>
        <form action="eliminarUsuario.php" method="POST">
            <div class="form-group">
                <label for="id_usuario">Seleccionar Usuario</label>
                <select id="id_usuario" name="id_usuario" required>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo htmlspecialchars($usuario['nombreUsuario']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="eliminarUsuario">Eliminar Usuario</button>
        </form>
        <?php
        // Mostrar mensajes si los hay
        if (isset($mensaje)) {
            echo "<p style='color:green;'>$mensaje</p>";
        }
        if (!empty($error)) {
            foreach ($error as $err) {
                echo "<p style='color:red;'>$err</p>";
            }
        }
        ?>
    </div>
</body>
</html>