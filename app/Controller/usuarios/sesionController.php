<?php


require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




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

            // En el controlador de sesión, después de verificar las credenciales
            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION["id_usuario"] = $usuario["id_usuario"];
                $_SESSION["nombreUsuario"] = $usuario["nombreUsuario"];
                $_SESSION["nivel_usuario"] = $usuario["nivel_usuario"];
                
                // Guardar la ruta de la imagen si existe
                if (!empty($usuario["ruta_imagen"])) {
                    $_SESSION["rutaImagen"] = $usuario["ruta_imagen"];
                }
                
                // Redirección al dashboard
                header("Location: ../../Views/usuarios/dashboard.php");
                exit();
            } else {
                // Manejo explícito cuando las credenciales son incorrectas
                $_SESSION["error"] = "Nombre de usuario o contraseña incorrectos.";
                header("Location: ../../../public/index.php");
                exit();
            }
        } else {
            // Si alguien intenta acceder directamente a este controlador sin POST
            header("Location: ../../../public/index.php");
            exit();
        }
    }
}

$loginController = new LoginController($conn);
$loginController->iniciarSesion();
?>
