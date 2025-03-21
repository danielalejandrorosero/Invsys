<?php

require_once '../config/cargarConfig.php';
session_start();

$error = [];




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  
    if (isset($_POST['actualizarUsuario'])) {
        $camposRequeridos = ['nombre', 'nombreUsuario'];
        validarCampos($camposRequeridos);

        if (empty($error)) {
            $nombre         = trim($_POST['nombre']);
            $nombreUsuario  = trim($_POST['nombreUsuario']);
            $id_usuario_post = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
            $id_usuario_sesion = $_SESSION['id_usuario'] ?? 0;
            $nivel_sesion      = $_SESSION['nivel_usuario'] ?? 0;

            if (empty($nombre) || empty($nombreUsuario)) {
                $error[] = "El nombre y el nombre de usuario no pueden estar vacíos.";
            }

            // Permitir actualizar si es el mismo usuario o si es administrador
            if ($id_usuario_post == $id_usuario_sesion || $nivel_sesion == 1) {
                if (!$conn) {
                    die("Error: Conexión no inicializada");
                }

                $sql = "UPDATE usuarios SET nombre = ?, nombreUsuario = ? WHERE id_usuario = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssi", $nombre, $nombreUsuario, $id_usuario_post);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        echo "Usuario actualizado exitosamente!";   
                    } else {
                        echo "No se realizaron cambios o hubo un error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error[] = "Error en la preparación de la consulta: " . $conn->error;
                }
            } else {
                $error[] = "No tienes permisos para actualizar este usuario.";
            }
        }
    }


    if (isset($_POST['actualizarPassword'])) {
        $camposRequeridos = ['password'];
        validarCampos($camposRequeridos);

        if (empty($error)) {
            $id_usuario_post   = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
            $id_usuario_sesion = $_SESSION['id_usuario'] ?? 0;
            $nivel_sesion      = $_SESSION['nivel_usuario'] ?? 0;

            if ($id_usuario_post == $id_usuario_sesion || $nivel_sesion == 1) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $password, $id_usuario_post);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        echo "Contraseña actualizada exitosamente!";
                    } else {
                        echo "No se realizaron cambios o hubo un error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error[] = "Error en la preparación de la consulta: " . $conn->error;
                }
            } else {
                $error[] = "No tienes permisos para actualizar la contraseña de este usuario.";
            }
        }
    }
}

// Mostrar errores acumulados, si existen
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p style='color:red;'>$err</p>";
    }
}

?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../frontend/editarUsuario.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        <form action="editarUsuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <button type="submit" name="actualizarUsuario">Actualizar Usuario</button>
        </form>
        <hr>
        <h2>Actualizar Contraseña</h2>
        <form action="editarUsuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="actualizarPassword">Actualizar Contraseña</button>
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