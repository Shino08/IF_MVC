<?php
declare(strict_types=1);

namespace App\Core;

/**
 * TasaBCV — Obtiene la tasa de cambio oficial del BCV vía DolarFlow API.
 * Cachea en sesión por 30 minutos para no llamar la API en cada request.
 * 
 * API: https://dolarflow.com/api/oficial/ (gratuita, sin API key)
 */
class TasaBCV
{
    private static string $cacheKey = 'bcv_tasa_data';
    private static string $cacheTs  = 'bcv_tasa_ts';
    private static int $cacheTTL    = 1800; // 30 minutos

    /**
     * Obtiene la tasa BCV actual.
     * @return array ['tasa' => float|null, 'fecha' => string|null, 'fuente' => string]
     */
    public static function getTasa(): array
    {
        // 1. Revisar caché en sesión
        if (
            isset($_SESSION[self::$cacheKey], $_SESSION[self::$cacheTs])
            && (time() - $_SESSION[self::$cacheTs]) < self::$cacheTTL
        ) {
            return $_SESSION[self::$cacheKey];
        }

        // 2. Llamar API DolarFlow
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 5,
                'header'  => 'User-Agent: InstalFuego/1.0'
            ]
        ]);

        $json = @file_get_contents('https://dolarflow.com/api/oficial/', false, $ctx);

        if ($json) {
            $data = json_decode($json, true);
            if (!empty($data['precio'])) {
                $result = [
                    'tasa'   => (float)$data['precio'],
                    'fecha'  => $data['fechaActualizacion'] ?? date('Y-m-d'),
                    'fuente' => 'BCV Oficial (DolarFlow)',
                ];
                self::guardarCache($result);
                return $result;
            }
        }

        // 3. Fallback: devolver última conocida o no disponible
        $result = [
            'tasa'   => $_SESSION[self::$cacheKey]['tasa'] ?? null,
            'fecha'  => $_SESSION[self::$cacheKey]['fecha'] ?? null,
            'fuente' => $_SESSION[self::$cacheKey]['fuente'] ?? 'No disponible — caché expirada',
        ];
        return $result;
    }

    /**
     * Formatea la tasa como string legible.
     */
    public static function formatTasa(?float $tasa): string
    {
        if ($tasa === null) return '—';
        return 'Bs. ' . number_format($tasa, 2, ',', '.') . ' / $';
    }

    /**
     * Convierte USD → Bs usando la tasa dada o la actual.
     */
    public static function convertirUsdABs(float $usd, ?float $tasa = null): ?float
    {
        $t = $tasa ?? (self::getTasa()['tasa'] ?? null);
        return $t !== null ? round($usd * $t, 2) : null;
    }

    /**
     * Convierte Bs → USD usando la tasa dada o la actual.
     */
    public static function convertirBsAUsd(float $bs, ?float $tasa = null): ?float
    {
        $t = $tasa ?? (self::getTasa()['tasa'] ?? null);
        return ($t !== null && $t > 0) ? round($bs / $t, 2) : null;
    }

    private static function guardarCache(array $data): void
    {
        $_SESSION[self::$cacheKey] = $data;
        $_SESSION[self::$cacheTs]  = time();
    }
}
