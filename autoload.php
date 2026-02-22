<?php
declare(strict_types=1);

/**
 * autoload.php – Raíz del proyecto
 *
 * Autocargador PSR-4 nativo para el miniframework MVC.
 *
 * Mapa de namespaces:
 *   App\Core\          → app/Core/
 *   App\Controllers\   → app/Controllers/
 *   App\Models\        → app/Models/
 */
spl_autoload_register(function (string $class): void {
    // Directorio raíz del proyecto (donde se encuentra este archivo)
    $baseDir = __DIR__ . '/';

    $prefixes = [
        'App\\Core\\'        => $baseDir . 'app/Core/',
        'App\\Controllers\\' => $baseDir . 'app/Controllers/',
        'App\\Models\\'      => $baseDir . 'app/Models/',
    ];

    foreach ($prefixes as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) !== 0) {
            
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $dir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
