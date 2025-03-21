
<?php
require_once '../config/cargarConfig.php';

// Iniciar sesión
session_start();


$error = [];

// Verificar si el usuario tiene el nivel requerido (1 en este caso)
nivelRequerido(1);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregarUsuario'])) {
    $camposRequeridos = ['email', 'nombre', 'nombreUsuario', 'password', 'nivel_usuario'];
    validarCampos($camposRequeridos);

    if (empty($error)) {
        // Limpiar y sanitizar los datos
        $nombre = trim($_POST['nombre']);
        $nombreUsuario = trim($_POST['nombreUsuario']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $nivel_usuario = intval($_POST['nivel_usuario']);
        $status = 1; // 1 = activo, 0 = inactivo
        $last_login = date('Y-m-d H:i:s');

        // Verificar si el nivel de usuario existe en la tabla 'grupos'
        $checkGroup = $conn->prepare("SELECT nivel_grupo FROM grupos WHERE nivel_grupo = ?");
        $checkGroup->bind_param("i", $nivel_usuario);
        $checkGroup->execute();
        $checkGroup->store_result();

        if ($checkGroup->num_rows === 0) {
            $error[] = "El nivel de usuario seleccionado no existe.";
        } else {
            // Insertar usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre, nombreUsuario, email, password, status, nivel_usuario, last_login) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiis", $nombre, $nombreUsuario, $email, $password, $status, $nivel_usuario, $last_login);

            if ($stmt->execute()) {
                // Redirección a listaUsuarios.php después de agregar exitosamente
                header("Location: listaUsuarios.php");
                exit();
            } else {
                $error[] = "Error al agregar usuario: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkGroup->close();
    } else {
        echo "<p style='color:red;'>Error: Campos requeridos no válidos.</p>";
    }
}

// Mostrar errores acumulados
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
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../frontend/agregarUsuario.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Agregar Usuario</h1>
        <form action="agregarUsuario.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="nivel_usuario">Nivel de Usuario</label>
                <select id="nivel_usuario" name="nivel_usuario" required>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario Regular</option>
                    <!-- Añadir más niveles según sea necesario -->
                </select>
            </div>
            <button type="submit" name="agregarUsuario">Agregar Usuario</button>
        </form>
        <?php
        // Mostrar errores acumulados
        if (!empty($error)) {
            foreach ($error as $err) {
                echo "<p style='color:red;'>$err</p>";
            }
        }
        ?>
    </div>
</body>
</html>