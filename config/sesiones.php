<?php

class Sesiones
{
    private $usuarioEstaAutenticado = false;
    private $session_lifetime = 900; // 900 es igual a 15 minutos

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->verificarExpiracion();
        $this->inicializarSesionUsuario();
    }

    private function verificarExpiracion()
    {
        if (
            isset($_SESSION["LAST_ACTIVITY"]) &&
            time() - $_SESSION["LAST_ACTIVITY"] > $this->session_lifetime
        ) {
            $this->cerrarSesion();
            header("Location: ../public/index.php");
            exit();
        }
        $_SESSION["LAST_ACTIVITY"] = time();
    }

    public function usuarioAutenticado()
    {
        return $this->usuarioEstaAutenticado;
    }

    public function iniciarSesion($id_usuario)
    {
        $_SESSION["id_usuario"] = $id_usuario;
        $_SESSION["LAST_ACTIVITY"] = time();
        $this->usuarioEstaAutenticado = true;
    }

    private function inicializarSesionUsuario()
    {
        if (isset($_SESSION["id_usuario"])) {
            $this->usuarioEstaAutenticado = true;
        }
    }

    public function cerrarSesion()
    {
        session_unset();
        session_destroy();
    }
}

$sesion = new Sesiones();
?>
