    <?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../vendor/autoload.php';


function enviarCorreoRecuperacion($correo, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'danielalejandroroseroortiz80@gmail.com';
        $mail->Password = 'pnze kxvb irtp jfvm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('danielalejandroroseroortiz80@gmail.com', 'Daniel Alejandro');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Recupera tu contraseña';
        $mail->Body = "<p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                       <p><a href='http://localhost/InventoryManagementSystem/usuarios/reiniciar_password.php?token=$token'>Recuperar Contraseña</a></p>";

        if ($mail->send()) {
            return true;  
        }


    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $mail->ErrorInfo); 
    }
    return false; 
}
