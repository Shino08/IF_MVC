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

    /**
     * Envía correo notificando que el pago de un pedido fue validado
     */
    public static function enviarCorreoPagoValidado(array $pedido): bool
    {
        try {
            $mail = self::make();
            
            $clienteEmail = $pedido['cliente_email'] ?? '';
            $clienteNombre = trim(($pedido['cliente_nombre'] ?? '') . ' ' . ($pedido['cliente_apellido'] ?? ''));
            
            if (empty($clienteEmail)) {
                return false;
            }

            $mail->addAddress($clienteEmail, $clienteNombre);
            $mail->isHTML(true);
            $mail->Subject = 'Pago validado - Pedido InstalFuego';

            $pedidoNum = 'PED-' . date('Y') . '-' . str_pad((string)($pedido['carrito_id'] ?? 0), 4, '0', STR_PAD_LEFT);

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-top: 4px solid #dc2626; border-radius: 8px; padding: 20px;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <img src='cid:logo_instalfuego' alt='InstalFuego' style='height: 80px;'>
                    </div>
                    <h2 style='color: #dc2626; text-align: center;'>Pago validado correctamente</h2>
                    <p>Hola, <strong>{$clienteNombre}</strong>.</p>
                    <p>El pago reportado para tu pedido <strong style='color: #dc2626;'>{$pedidoNum}</strong> ha sido validado exitosamente por el equipo de InstalFuego.</p>
                    <p>Tu pedido ahora se encuentra en estado: <strong style='color: #2563eb;'>Procesando</strong>.</p>
                    <p>Puedes ingresar a tu cuenta en nuestro sistema para revisar el detalle de tu solicitud.</p>
                    <br>
                    <p>Gracias por confiar en nosotros.</p>
                    <p>Saludos,<br><strong style='color: #dc2626;'>Equipo de InstalFuego C.A.</strong></p>
                </div>
            ";

            $logoPath = dirname(__DIR__, 2) . '/public/img/Photoroom-20251106_165742.png';
            if (file_exists($logoPath)) {
                $mail->AddEmbeddedImage($logoPath, 'logo_instalfuego', 'logo.png');
            }

            return $mail->send();
        } catch (Exception $e) {
            error_log('Error enviando correo de pago validado: ' . $e->getMessage());
            return false;
        }
    }
}
