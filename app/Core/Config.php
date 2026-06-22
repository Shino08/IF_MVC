<?php
declare(strict_types=1);

namespace App\Core;

class Config
{
    public const SMTP_HOST = 'smtp.mailtrap.io';
    public const SMTP_USER = 'test';
    public const SMTP_PASS = 'test';
    public const SMTP_PORT = 2525;

    // ── WhatsApp ────────────────────────────────────────────────────
    /** Número de contacto (solo dígitos, sin + ni espacios, para URL) */
    public const WHATSAPP_NUMBER = '584121234567';
    /** Número formateado para mostrar al usuario */
    public const WHATSAPP_DISPLAY = '+58 412-1234567';

    /**
     * Genera URL de WhatsApp con mensaje predefinido.
     * @param string $extra Texto opcional (crudo, sin codificar) para añadir al mensaje.
     */
    public static function whatsappUrl(string $extra = ''): string
    {
        $msg = 'Hola, quiero coordinar el pago de mi cotización';
        if ($extra) {
            $msg .= ' - ' . $extra;
        }
        return 'https://wa.me/' . self::WHATSAPP_NUMBER . '?text=' . rawurlencode($msg);
    }
}
