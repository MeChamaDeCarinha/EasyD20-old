<?php

namespace Src\Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PDO;

class Mail {
    public static function emailVerify($usuario) {
        \Dotenv\Dotenv::createImmutable("./")->load();

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $url = URL;
        $codigo = random_int(100000, 999999);

        try {
            //Server settings
            //$mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->CharSet    = "UTF-8"; 
            $mail->Host       = $_ENV["MAIL_HOST"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV["MAIL_USER"];
            $mail->Password   = $_ENV["MAIL_PASS"];
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('easyd20.contato@gmail.com', 'EasyD20'); // Quem envia
            $mail->addReplyTo('easyd20.contato@gmail.com', 'EasyD20'); // Quem envia

            $mail->addAddress($usuario->email, $usuario->nome); // Quem recebe
            
            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Verificar email';
            $mail->Body    = "
                
                <p style='font-size:26px;color:#AF5AD1;text-decoration:none'>EasyD20</p>
                <p style=font-size:22px>Seu código de verificação é: <span style='color:#AF5AD1'>{$codigo}</span><br><span style='color:#000000'>Caso não tenha sido você ignore este email.</span></p>
                ";
            $mail->AltBody = 'Seu email não possui suporte para HTML';

            $mail->send();
            return $codigo;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public static function passRecovery($usuario) {
        \Dotenv\Dotenv::createImmutable("./")->load();

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $url = URL;
        $codigo = random_int(100000, 999999);

        try {
            //Server settings
            //$mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->CharSet    = "UTF-8"; 
            $mail->Host       = $_ENV["MAIL_HOST"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV["MAIL_USER"];
            $mail->Password   = $_ENV["MAIL_PASS"];
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('easyd20.contato@gmail.com', 'EasyD20'); // Quem envia
            $mail->addReplyTo('easyd20.contato@gmail.com', 'EasyD20'); // Quem envia

            $mail->addAddress($usuario->email, $usuario->nome); // Quem recebe
            
            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Código de recuperação';
            $mail->Body    = "
                <a href='{ $url }' style='font-size:26px;color:#AF5AD1;text-decoration:none'>EasyD20</a>
                <p style=font-size:22px>Seu código de recuperação é: <span style='color:#AF5AD1'>{$codigo}</span><br><span style='color:#000000'>Caso não tenha sido você ignore este email.</span></p>
                ";
            $mail->AltBody = 'Seu email não possui suporte para HTML';

            $mail->send();
            return $codigo;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}