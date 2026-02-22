<?php
declare(strict_types=1);

/**
 * Front Controller – punto de entrada único de la aplicación.
 * Toda petición es redirigida aquí por el .htaccess de la carpeta public/.
 */

session_start();

// 1. Cargar el autocargador de clases (ubicado en la raíz del proyecto)
require_once dirname(__DIR__) . '/autoload.php';

// 2. Importar el Router y los controladores
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

// ─── Rutas de Autenticación ───────────────────────────────────────────────────
Router::get('/login',    [AuthController::class, 'showLogin']);
Router::post('/login',   [AuthController::class, 'processLogin']);
Router::get('/logout',   [AuthController::class, 'logout']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register',[AuthController::class, 'register']);

// ─── Rutas Principales ────────────────────────────────────────────────────────
Router::get('/', [HomeController::class, 'index']);

Router::get('/hola/{nombre}', function (string $nombre): void {
    echo 'Hola ' . htmlspecialchars(ucfirst($nombre));
});

Router::get('/contacto', function (): void {
    echo 'Página de contacto';
});

// ─── Despachar la petición ───────────────────────────────────────────────────
Router::dispatch();