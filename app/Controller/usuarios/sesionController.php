<?php
// debugear
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";

session_start();

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
                header("Location: ../../../Views/usuarios/login.php");

                exit();
            }

            if (empty($password)) {
                $_SESSION["error"] = "La contraseña no puede estar vacía.";
                header("Location: ../../Views/login.php");
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
                header("Location: ../../Views/usuarios/index.php");
                exit();
            } else {
                $_SESSION["error"] = "Usuario o contraseña incorrectos.";
                header("Location: ../../Views/usuarios/login.php");
                exit();
            }
        }
    }
}

$loginController = new LoginController($conn);
$loginController->iniciarSesion();
?>
