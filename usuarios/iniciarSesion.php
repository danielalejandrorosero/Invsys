<?php





require_once '../config/db_config.php';
require_once '../config/db_connect.php';
require_once '../config/funciones.php';
require_once '../config/sesiones.php';

$_SESSION['ultimoAcceso'] = time();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (empty($_POST['nombreUsuario']) || empty($_POST['password'])) {
            throw new Exception("Credenciales inválidas.");
        }

        $nombreUsuario = trim($_POST['nombreUsuario']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT id_usuario, password, nivel_usuario FROM usuarios WHERE nombreUsuario = ?");
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            throw new Exception("Credenciales inválidas.");
        }

        session_regenerate_id(true); // Seguridad

        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombreUsuario'] = $nombreUsuario;
        $_SESSION['nivel_usuario'] = $usuario['nivel_usuario'];

        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../usuarios/iniciarSesion.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../frontend/login.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form action="iniciarSesion.php" method="POST">
            <div class="form-group">
                <label for="nombreUsuario">Nombre de Usuario</label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red;'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>
</html>