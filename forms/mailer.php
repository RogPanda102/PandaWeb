<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas a PHPMailer
require __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../config/app.php'; // Configuración general (ocultar errores, etc.)


function enviarCorreo($nombre, $correo, $asunto, $mensaje) {

    $mail = new PHPMailer(true);

    try {

        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = getenv('MAIL_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('MAIL_USER');
        $mail->Password   = getenv('MAIL_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = getenv('MAIL_PORT');

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
            ],
        ];

        // Codificación
        $mail->CharSet = 'UTF-8';

        // Emisor
        $mail->setFrom(getenv('MAIL_USER'), 'Formulario Web');
        $mail->addAddress(getenv('MAIL_USER'));
        $mail->addReplyTo($correo, $nombre);

        // Receptor (puede ser cualquiera en pruebas)
        $mail->addAddress(getenv('MAIL_USER'));

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
        echo $mail->ErrorInfo;
        exit;
    }
}
