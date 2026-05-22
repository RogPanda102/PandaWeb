<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas a PHPMailer
require __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../config/app.php'; // Configuración general (ocultar errores, etc.)

function crearMailer(){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = getenv('MAIL_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('MAIL_USER');
    $mail->Password   = getenv('MAIL_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = getenv('MAIL_PORT');
    $mail ->SMTPOptions = [
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false,
        ],
    ];
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    return $mail;
}

function enviarCorreoEmpresa($nombre, $correo, $asunto, $mensaje) {

    

    try {
        $mail = crearMailer();
        
        $mail->setFrom(getenv('MAIL_USER'), 'Formulario Web');
        $mail->addAddress(getenv('MAIL_USER'));
        $mail->addReplyTo($correo, $nombre);

        // Contenido del correo
        $mail->Subject = "Nuevo mensaje: $asunto";

        $mail->Body = "
            <h3>Nuevo mensaje de contacto</h3>
            <p><b>Nombre:</b> {$nombre}</p>
            <p><b>Email:</b> {$correo}</p>
            <p><b>Mensaje:</b><br>{$mensaje}</p>
        ";

        $mail->AltBody = "
            Nombre: {$nombre}
            Email: {$correo}
            Mensaje: {$mensaje}
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

function enviarCorreoCliente($nombre, $correo){

    try {
        $mail = crearMailer();
        
        $mail->setFrom(getenv('MAIL_USER'), 'Formulario Web');
        $mail->addAddress($correo, $nombre);

        // Contenido del correo
        $mail->Subject = "Gracias por contactarnos";

        $mail->Body = "
            <div style='
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: auto;
                padding: 40px;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #e5e7eb;
                '>

                <h1 style='color:#111827; margin-bottom:20px;'>
                    Hola {$nombre} 👋
                </h1>

                <p style='color:#374151; line-height:1.7;'>
                    Gracias por contactar a <strong>PandaSoft</strong>.
                </p>

                <p style='color:#374151; line-height:1.7;'>
                    Hemos recibido correctamente tu mensaje y nuestro equipo revisará tu solicitud lo antes posible.
                </p>

                <p style='color:#374151; line-height:1.7;'>
                    Te responderemos a la brevedad.
                </p>

                <br>

                <p style='color:#6b7280; font-size:14px;'>
                    Este es un correo automático de confirmación.
                </p>

                <hr style='margin:30px 0;'>

                <p style='color:#111827; font-weight:bold;'>
                    PandaSoft
                </p>

            </div>
        ";

        $mail->AltBody = "
            Hola {$nombre},
            Gracias por contactarnos. Hemos recibido tu mensaje y nos pondremos en contacto contigo lo antes posible.
            Saludos,
            El equipo de PandaSoft
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }

}
