<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';

class UsuariosController {
    private $usuarioModel;
    private $errores = [];

    public function __construct($conn) {


        $this->usuarioModel = new Usuario($conn);
        nivelRequerido([1]);

    }

    public function agregarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregarUsuario'])) {
            $nombre = trim($_POST['nombre'] ?? '');
            $nombreUsuario = trim($_POST['nombreUsuario'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $nivel_usuario = intval($_POST['nivel_usuario'] ?? 0);

            if (empty($nombre) || empty($nombreUsuario) || empty($email) || empty($password) || $nivel_usuario === 0) {
                $_SESSION['error'] = "Todos los campos son requeridos.";
                header("Location: ../../Views/usuarios/agregarUsuarioVista.php");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Formato de email inválido.";
                header("Location: ../../Views/usuarios/agregarUsuarioVista.php");
                exit();
            }

            $resultado = $this->usuarioModel->agregarUsuario($nombre, $nombreUsuario, $email, $password, $nivel_usuario);

            if ($resultado === true) {
                header("Location: listarUsuarios.php");
                exit();
            } else {
                $_SESSION['error'] = $resultado;
                header("Location: ../../Views/usuarios/agregarUsuarioVista.php");
                exit();
            }
        }

        require_once '../../Views/usuarios/agregarUsuarioVista.php';
    }
}

// Instanciar el controlador
$usuariosController = new UsuariosController($conn);
$usuariosController->agregarUsuario();

?>