<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../config/');
$dotenv->load();

class MailService {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configurarSMTP();
    }
    
    private function configurarSMTP() {
        try {
            // Configuración del servidor
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['MAIL_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['MAIL_USERNAME'];
            $this->mail->Password = $_ENV['MAIL_PASSWORD'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = (int)$_ENV['MAIL_PORT'];
            
            // Configuración del remitente
            $this->mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            
            // Configuración general
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("Error al configurar SMTP: " . $e->getMessage());
        }
    }
    
    public function enviarCorreoRecuperacion($correo, $token) {
        try {
            // Limpiar destinatarios anteriores
            $this->mail->clearAddresses();
            
            // Agregar destinatario
            $this->mail->addAddress($correo);
            
            // Configurar contenido
            $this->mail->Subject = 'Recupera tu contraseña';
            
            // Obtener la URL base dinámicamente
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
            
            // Construir la URL completa
            $resetUrl = $protocol . '://' . $host . $_ENV['APP_URL'] . '/app/Views/usuarios/recuperarPassword.php?token=' . $token;
            
            $this->mail->Body = "<p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                       <p><a href='{$resetUrl}'>Recuperar Contraseña</a></p>";
            
            // Enviar correo
            if ($this->mail->send()) {
                return true;
            }
            
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $this->mail->ErrorInfo);
        }
        return false;
    }
}