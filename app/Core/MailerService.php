<?php
declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    /**
     * Crea y devuelve una instancia configurada de PHPMailer.
     * @return PHPMailer
     */
    public static function make(): PHPMailer
    {
        $mail = new PHPMailer(true);

        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER') ?: 'tu_correo@gmail.com';
        $mail->Password   = getenv('SMTP_PASS') ?: '';
        
        $encryption = getenv('SMTP_ENCRYPTION') ?: 'tls';
        $mail->SMTPSecure = ($encryption === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int) (getenv('SMTP_PORT') ?: 587);

        // Charset para evitar problemas con acentos y ñ
        $mail->CharSet    = 'UTF-8';

        // Remitente por defecto
        $fromEmail = getenv('SMTP_FROM_EMAIL') ?: 'no-reply@instalfuego.com';
        $fromName  = getenv('SMTP_FROM_NAME') ?: 'InstalFuego';
        
        try {
            $mail->setFrom($fromEmail, $fromName);
        } catch (Exception $e) {
            // Manejar la excepción silenciosamente o hacer log
        }

        return $mail;
    }
}
