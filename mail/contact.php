<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(500);
    exit('Error: Los datos del formulario son inválidos.');
}

$nombre = htmlspecialchars($_POST['name']);
$mail = htmlspecialchars($_POST['email']);
$asunto = htmlspecialchars($_POST['subject']);
$mensaje = htmlspecialchars($_POST['message']);

$oMail = new PHPMailer(true);



try {
    $oMail->SMTPDebug = 0;
    $oMail->isSMTP();
    $oMail->Host = 'smtp.zoho.com';
    $oMail->SMTPAuth = true;
    $oMail->Username = "ayuda@compramelo.site";
    $oMail->Password = "Cere47RCKg3k";
    $oMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $oMail->Port = 465;

    $oMail->setFrom('ayuda@compramelo.site', "Soporte de Ayuda");
    $oMail->addAddress('ayuda@compramelo.site', $nombre);

    $oMail->isHTML(true);
    $oMail->Subject = $asunto;
    $oMail->Body = '
    Se ha enviado un nuevo mensaje desde el formulario de contacto del sitio web compramelo.site.<br>
    <br>
    Detalles:<br>
    Nombre: '.$nombre.'<br>
    Email: '.$mail.'<br>
    Mensaje: '.$mensaje.'"
    ';

    // Adjuntar archivo si se ha proporcionado
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['archivo']['tmp_name'];
        $file_name = $_FILES['archivo']['name'];
        $oMail->addAttachment($file_tmp_name, $file_name);
    }

    $oMail->send();
    echo 'El mensaje se ha enviado';

    try{
        $mensajeAgradecimiento = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Respuesta Automática</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background-color: #70bbd9; padding: 20px; border-radius: 10px; text-align: center;">
                    <h1 style="font-size: 24px; color: #ffffff;">Hola '.$nombre.'</h1>
                </div>

                <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 24px;">
                    ¡Gracias por contactar a Compramelo! Hemos recibido su mensaje y nos pondremos en contacto con usted lo antes posible.
                </p>
                <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 24px;">
                    Mensaje que nos envió:
                </p>

                <div style="background-color: #ffffff; border: 2px solid #70bbd9; border-radius: 10px; padding: 20px; margin-top: 20px;">
                    <p style="margin: 0; font-size: 16px; line-height: 24px;">

                        Asunto: '.$asunto.'<br>
                        Mensaje: '.$mensaje.'
                    </p>
                </div>

                <p style="margin: 20px 0 0 0; font-size: 16px; line-height: 24px;">
                    Correo electrónico del usuario: '.$mail.'
                </p>
                <p style="margin: 20px 0 0 0; font-size: 16px; line-height: 24px;">
                    ¡Esperamos conversar pronto!
                </p>

            </div>
        </body>
        </html>
        '; 

        $oMail->clearAddresses();
        $oMail->addAddress($mail,$nombre);
        $oMail->Subject = "Gracias por ponerte en contacto con nosotros";
        $oMail->Body = $mensajeAgradecimiento;

        $oMail->send();

        echo 'otro trabajo bien hecho';
    }catch(Exception $e){
        echo 'el mensaje no se pudo enviar (╯‵□′)╯︵┻━┻ Error: '.$oMail->ErrorInfo.'';
    }
    
} catch (Exception $e) {
    echo "El Mensaje no se envió (。﹏。*) Error: {$oMail->ErrorInfo}";
}


?>
