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
use App\Controllers\DashboardController;

// ─── Rutas de Autenticación ───────────────────────────────────────────────────
Router::get('/login',    [AuthController::class, 'showLogin']);
Router::post('/login',   [AuthController::class, 'processLogin']);
Router::get('/logout',   [AuthController::class, 'logout']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register',[AuthController::class, 'register']);

// ─── Rutas del Panel Admin (Dashboard) ───────────────────────────────────────
Router::get('/dashboard',                      [DashboardController::class, 'index']);
Router::get('/dashboard/productos',            [DashboardController::class, 'productos']);
Router::get('/dashboard/productos/agregar',    [DashboardController::class, 'agregarProducto']);
Router::get('/dashboard/productos/editar/{id}',[DashboardController::class, 'editarProducto']);
Router::get('/dashboard/categorias',           [DashboardController::class, 'categorias']);
Router::get('/dashboard/cotizaciones',         [DashboardController::class, 'cotizaciones']);
Router::get('/dashboard/servicios',            [DashboardController::class, 'servicios']);
Router::get('/dashboard/reportes',             [DashboardController::class, 'reportes']);

// ─── Rutas Públicas ───────────────────────────────────────────────────────────
Router::get('/', [HomeController::class, 'index']);

// ─── Despachar la petición ───────────────────────────────────────────────────
Router::dispatch();