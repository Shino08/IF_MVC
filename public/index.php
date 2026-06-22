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
use App\Controllers\CuentaController;
use App\Controllers\CotizacionClienteController;
use App\Controllers\CatalogoController;

Router::get('/login',    [AuthController::class, 'showLogin']);
Router::post('/login',   [AuthController::class, 'processLogin']);
Router::get('/logout',   [AuthController::class, 'logout']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register',[AuthController::class, 'register']);
Router::get('/olvide-password', [AuthController::class, 'showOlvidePassword']);
Router::post('/olvide-password',[AuthController::class, 'processOlvidePassword']);
Router::get('/reset-password',  [AuthController::class, 'showResetPassword']);
Router::post('/reset-password', [AuthController::class, 'processResetPassword']);

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

Router::get('/cuenta',                   [CuentaController::class, 'index']);
Router::get('/cuenta/perfil',            [CuentaController::class, 'perfil']);
Router::post('/cuenta/perfil',           [CuentaController::class, 'updatePerfil']);
Router::get('/cuenta/seguridad',         [CuentaController::class, 'seguridad']);
Router::post('/cuenta/seguridad',        [CuentaController::class, 'updateSeguridad']);

Router::get('/cotizacion/actual',             [CotizacionClienteController::class, 'actual']);
Router::post('/cotizacion/agregar',           [CotizacionClienteController::class, 'agregar']);
Router::post('/cotizacion/item/actualizar',   [CotizacionClienteController::class, 'actualizarItem']);
Router::post('/cotizacion/item/eliminar',     [CotizacionClienteController::class, 'eliminarItem']);
Router::post('/cotizacion/enviar',            [CotizacionClienteController::class, 'enviar']);
Router::get('/cotizacion/exito',              [CotizacionClienteController::class, 'exito']);
Router::get('/mis-cotizaciones',              [CotizacionClienteController::class, 'historial']);
Router::get('/mis-cotizaciones/{id}',         [CotizacionClienteController::class, 'detalle']);

Router::get('/catalogo', [CatalogoController::class, 'index']);
Router::get('/producto/{id}', [CatalogoController::class, 'producto']);
Router::get('/servicio/{id}', [CatalogoController::class, 'servicio']);

Router::get('/', [HomeController::class, 'index']);

Router::dispatch();