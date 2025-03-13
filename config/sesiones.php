<?php
session_start();

class Sesiones {
    private $usuarioEstaAutenticado = false;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->inicializarSesionUsuario();
    }

    public function usuarioAutenticado() {
        return $this->usuarioEstaAutenticado;
    }

    public function iniciarSesion($id_usuario) {
        $_SESSION['id_usuario'] = $id_usuario;
        $this->usuarioEstaAutenticado = true;
    }

    private function inicializarSesionUsuario() {
        if (isset($_SESSION['id_usuario'])) {
            $this->usuarioEstaAutenticado = true;
        }
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
    }
}

$sesion = new Sesiones();
