<?php
declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductoController;
use App\Controllers\CategoriasController;
use App\Controllers\ServicioController;

Router::get('/login',    [AuthController::class, 'showLogin']);
Router::post('/login',   [AuthController::class, 'processLogin']);
Router::get('/logout',   [AuthController::class, 'logout']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register',[AuthController::class, 'register']);

Router::get('/dashboard',                      [DashboardController::class, 'index']);

Router::get('/dashboard/productos',                    [DashboardController::class, 'productos']);
Router::get('/dashboard/productos/agregar',            [DashboardController::class, 'agregarProducto']);
Router::post('/dashboard/productos/store',             [ProductoController::class, 'store']);
Router::get('/dashboard/productos/editar/{id}',        [DashboardController::class, 'editarProducto']);
Router::post('/dashboard/productos/actualizar/{id}',   [ProductoController::class, 'update']);
Router::post('/dashboard/productos/eliminar',          [ProductoController::class, 'delete']);
Router::post('/dashboard/productos/imagen/borrar',     [ProductoController::class, 'deleteImage']);
Router::post('/dashboard/productos/imagen/reemplazar', [ProductoController::class, 'replaceImage']);

Router::get('/dashboard/categorias',           [DashboardController::class, 'categorias']);
Router::post('/dashboard/categorias/store',    [App\Controllers\CategoriasController::class, 'store']);
Router::post('/dashboard/categorias/delete',   [App\Controllers\CategoriasController::class, 'delete']);

Router::get('/dashboard/cotizaciones',         [DashboardController::class, 'cotizaciones']);

Router::get('/dashboard/servicios',                      [DashboardController::class,  'servicios']);
Router::get('/dashboard/servicios/agregar',              [DashboardController::class,  'agregarServicio']);
Router::post('/dashboard/servicios/store',               [ServicioController::class,   'store']);
Router::get('/dashboard/servicios/editar/{id}',          [DashboardController::class,  'editarServicio']);
Router::post('/dashboard/servicios/actualizar/{id}',     [ServicioController::class,   'update']);
Router::post('/dashboard/servicios/eliminar',            [ServicioController::class,   'delete']);
Router::post('/dashboard/servicios/imagen/borrar',       [ServicioController::class,   'deleteImagen']);
Router::post('/dashboard/servicios/imagen/reemplazar',   [ServicioController::class,   'reemplazarImagen']);

Router::get('/dashboard/reportes',             [DashboardController::class, 'reportes']);

Router::get('/', [HomeController::class, 'index']);

Router::dispatch();