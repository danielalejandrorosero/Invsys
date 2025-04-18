<?php


require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';

nivelRequerido(1);
class ListarUsuarios {
    

    private $usuarioModel;
    
    public function __construct($conn) {
        $this->usuarioModel = new Usuario($conn);
    }  

    public function listarUsuarios() {
        try {
            $usuarios = $this->usuarioModel->obtenerUsuarios();
            require_once __DIR__ . '/../../Views/usuarios/listarUsuariosVista.php';
        } catch (Exception $e) {
            echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
        }
    }
}

// Instanciar el controlador
$listarUsuarios = new ListarUsuarios($conn);
$listarUsuarios->listarUsuarios();
