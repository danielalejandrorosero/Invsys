<?php

require_once __DIR__ . "/../../../config/cargarConfig.php";
require_once __DIR__ . "/../../Models/usuarios/Usuarios.php";

nivelRequerido(1);

class EliminarUsuario
{
    private $usuarioModel;
    public function __construct($conn)
    {
        $this->usuarioModel = new Usuario($conn);
    }

    public function eliminarUsuario()
    {
        $error = [];
        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["eliminarUsuario"])
        ) {
            $id_usuario = isset($_POST["id_usuario"])
                ? (int) $_POST["id_usuario"]
                : 0;

            $resultado = $this->usuarioModel->eliminarUsuario($id_usuario);

            if ($resultado === true) {
                $_SESSION["mensaje"] = "Usuario eliminado exitosamente.";
                header(
                    "Location: ../../Controller/usuarios/listarUsuarios.php"
                );
                exit();
            } else {
                $error[] = "No se encontró el usuario o error al eliminar.";
            }
        }

        // obtener lista de usuarios
        $usuarios = $this->usuarioModel->obtenerNombreUsuario();

        require_once __DIR__ . "/../../Views/usuarios/eliminarUsuarioVista.php";
    }
}

$controller = new EliminarUsuario($conn);
$controller->eliminarUsuario();

?>
