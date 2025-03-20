<?php
session_set_cookie_params([
    'lifetime' => 900,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
require_once '../config/cargarConfig.php';

// Verificar tiempo de inactividad
if (isset($_SESSION['ultimoAcceso']) && (time() - $_SESSION['ultimoAcceso']) > 900) {
    session_unset();
    session_destroy();
    die("Sesión expirada. Vuelve a iniciar sesión.");
}

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

        header("Location: ../index.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../usuarios/login.php");
        exit();
    }
}
?>
