<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';
require_once __DIR__ . '/configPHPMailer.php';




class SolicitarRecuperacionController {
    private $usuarioModel;
    private $mailService;

    public function __construct($conn) {
        $this->usuarioModel = new Usuario($conn);
        $this->mailService = new MailService();
    }

    public function solicitarRecuperacion() {
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo']);
            $resultado = $this->usuarioModel->generarToken($correo);

            if (is_array($resultado)) {
                $token = $resultado['token'];
                if ($this->mailService->enviarCorreoRecuperacion($correo, $token)) {
                    $mensaje = "Correo de recuperación enviado.";
                } else {
                    $mensaje = "Error al enviar el correo.";
                }
            } else {
                $mensaje = $resultado;
            }
        }

        require_once __DIR__ . '/../../Views/usuarios/solicitarCorreoVista.php';
    }
}

$controller = new SolicitarRecuperacionController($conn);
$controller->solicitarRecuperacion();






?>