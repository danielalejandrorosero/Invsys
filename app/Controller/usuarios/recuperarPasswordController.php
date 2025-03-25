<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';
require_once __DIR__ . '/configPHPMailer.php';

date_default_timezone_set('America/Bogota');

class RecuperarPasswordController {

    private $usuarioModel;

    public function __construct($conn) {
        $this->usuarioModel = new Usuario($conn);
    }

    public function recuperarPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = trim($_POST['token']);
            $nuevoPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $resultado = $this->usuarioModel->actualizarPasswordConToken($token, $nuevoPassword);

            if ($resultado === true) {
                $mensaje = "Contraseña actualizada con éxito.";
                header("Location: ../../Views/usuarios/login.php");
                exit();
            } else {
                $mensaje = $resultado;
            }
        } else if (isset($_GET['token'])) {
            $token = trim($_GET['token']);
        } else {
            header("Location: solicitar_recuperacion.php");
            exit();
        }


        // recargar la vista
        require_once __DIR__ . '/../../Views/usuarios/recuperarPassword.php';
    }
}

$recuperarPassword = new RecuperarPasswordController($conn);
$recuperarPassword->recuperarPassword();



?>