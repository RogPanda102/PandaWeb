<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas a PHPMailer
require __DIR__ . '/../assets/vendor/php-email-form/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../assets/vendor/php-email-form/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../assets/vendor/php-email-form/PHPMailer-master/src/SMTP.php';

function enviarCorreo($nombre, $correo, $asunto, $mensaje) {

    $mail = new PHPMailer(true);

    try {

        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '65c4fa679159a4';
        $mail->Password   = '9e07ab51006814';
        $mail->Port       = 2525;

        // Codificación
        $mail->CharSet = 'UTF-8';

        // Emisor
        $mail->setFrom('no-reply@empresa.com', 'Formulario Web');

        // Receptor (puede ser cualquiera en pruebas)
        $mail->addAddress('pruebas@empresa.com');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;

        $mail->Body = "
            <h3>Nuevo mensaje de contacto</h3>
            <p><b>Nombre:</b> $nombre</p>
            <p><b>Email:</b> $correo</p>
            <p><b>Mensaje:</b><br>$mensaje</p>
        ";

        $mail->AltBody = "
            Nombre: $nombre
            Email: $correo
            Mensaje: $mensaje
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}
