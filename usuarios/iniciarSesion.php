<?php
session_start(); // Debe ser lo primero
require_once '../config/cargarConfig.php';

// Configuración robusta de cookies de sesión
session_set_cookie_params([
    'lifetime' => 900,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Verificar tiempo de inactividad
if (isset($_SESSION['ultimoAcceso'])) {
    $tiempoTranscurrido = time() - $_SESSION['ultimoAcceso'];
    if ($tiempoTranscurrido > 900) {
        session_unset();
        session_destroy();
        die("Sesión expirada. Vuelve a iniciar sesión.");
    }
}

// Actualizar tiempo de acceso
$_SESSION['ultimoAcceso'] = time();

// Procesar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validar campos
        if (empty($_POST['nombreUsuario']) || empty($_POST['password'])) {
            throw new Exception("Credenciales inválidas.");
        }

        $nombreUsuario = trim($_POST['nombreUsuario']);
        $password = trim($_POST['password']);

        // Buscar usuario
        $stmt = $conn->prepare("SELECT id_usuario, password, nivel_usuario FROM usuarios WHERE nombreUsuario = ?");
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $usuario = $resultado->fetch_assoc();
        
        // Validar credenciales sin revelar información
        if (!$usuario || !password_verify($password, $usuario['password'])) {
            throw new Exception("Credenciales inválidas.");
        }

        // Establecer sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombreUsuario'] = $nombreUsuario;
        $_SESSION['nivel_usuario'] = $usuario['nivel_usuario'];

        // Respuesta exitosa para CURL
        echo "Sesión iniciada correctamente. PHPSESSID=".session_id();

    } catch (Exception $e) {
        echo "Error: ".$e->getMessage();
    }
    
    exit();
}
