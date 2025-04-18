<?php

// Ver errores

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";

// Ya inicio sesion por el require_once de cargarConfig.php

class EditarUsuarioController
{
    private $usuarioModel;

    public function __construct($conn)
    {
        $this->usuarioModel = new Usuario($conn);
    }

    public function editarUsuario()
    {
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["actualizarUsuario"])
        ) {
            $id_usuario = isset($_POST["id_usuario"])
                ? (int) $_POST["id_usuario"]
                : 0;
            $nombre = trim($_POST["nombre"] ?? "");
            $nombreUsuario = trim($_POST["nombreUsuario"] ?? "");

            $id_usuario_sesion = $_SESSION["id_usuario"] ?? 0;
            $nivel_sesion = $_SESSION["nivel_usuario"] ?? 0;

            // Validar permisos
            if ($id_usuario_sesion != $id_usuario && $nivel_sesion != 1) {
                $_SESSION["error"] =
                    "No tienes permisos para editar este usuario.";
                header("Location: ../../Views/usuarios/dashboard.php");
                exit();
            }

            // Verificar si el nombre de usuario ya existe
            if (
                $this->usuarioModel->nombreUsuarioExiste(
                    $nombreUsuario,
                    $id_usuario
                )
            ) {
                $_SESSION["error_nombreUsuario"] =
                    "El nombre de usuario ya existe.";
                header("Location: ../../Views/usuarios/editarUsuarioVista.php");
                exit();
            }

            // Llamar a la db
            $resultado = $this->usuarioModel->editarUsuario(
                $id_usuario,
                $nombre,
                $nombreUsuario,
                $id_usuario_sesion,
                $nivel_sesion
            );

            if ($resultado === true) {
                $_SESSION["mensaje"] = "Usuario actualizado exitosamente.";
                header("Location: ../../../public/index.php");
                exit();
            } else {
                $_SESSION["error"] = $resultado;
                header("Location: ../../Views/usuarios/editarUsuarioVista.php");
                exit();
            }
        } else {
            // Obtener datos del usuario
            $id_usuario = $_SESSION["id_usuario"];
            $usuario = $this->usuarioModel->obtenerUsuarioPorId($id_usuario);

            // Recargar la vista con los datos del usuario
            require_once __DIR__ .
                "/../../Views/usuarios/editarUsuarioVista.php";
        }
    }
}

$editarUsuario = new EditarUsuarioController($conn);
$editarUsuario->editarUsuario();
