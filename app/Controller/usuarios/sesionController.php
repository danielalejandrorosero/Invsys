<?php


require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";




class LoginController
{
    private $usuarioModel;

    public function __construct($conn)
    {
        $this->usuarioModel = new Usuario($conn);
    }

    public function iniciarSesion()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
            $nombreUsuario = trim($_POST["nombreUsuario"] ?? "");
            $password = trim($_POST["password"] ?? "");

            if (empty($nombreUsuario)) {
                $_SESSION["error"] =
                    "El nombre de usuario no puede estar vacío.";
                header("Location: ../../../public/index.php");

                exit();
            }

            if (empty($password)) {
                $_SESSION["error"] = "La contraseña no puede estar vacía.";
                header("Location: ../../../public/index.php");
                exit();
            }

            $usuario = $this->usuarioModel->verificarCredenciales(
                $nombreUsuario,
                $password
            );

            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nombreUsuario"] = $usuario["nombreUsuario"];
                $_SESSION["nivel_usuario"] = $usuario["nivel_usuario"];
                // index
                header("Location: ../../Views/usuarios/dashboard.php");
                exit();
            } else {
                $_SESSION["error"] = "Usuario o contraseña incorrectos.";
                header("Location: ../../../public/index.php");
                exit();
            }
        }
    }
}

$loginController = new LoginController($conn);
$loginController->iniciarSesion();
?>
