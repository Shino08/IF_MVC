<?php
declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/autoload.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

\App\Core\Env::load(dirname(__DIR__) . '/.env');

if (isset($_SESSION['user_id'])) {
    $userModel = new \App\Models\UsersModel();
    $user = $userModel->findById((int)$_SESSION['user_id']);
    if (!$user || !isset($_SESSION['session_token']) || $user['session_token'] !== $_SESSION['session_token']) {
        $_SESSION = [];
        session_destroy();
    }
}

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

Router::get('/dashboard/cotizaciones',              [DashboardController::class, 'cotizaciones']);
Router::get('/dashboard/detalle-solicitud/{id}',      [DashboardController::class, 'detalleSolicitud']);
Router::post('/dashboard/cotizaciones/procesar',      [DashboardController::class, 'procesarCotizacion']);
Router::post('/dashboard/cotizaciones/validar-pago',  [DashboardController::class, 'validarPago']);
Router::post('/dashboard/cotizaciones/actualizar-logistica', [DashboardController::class, 'actualizarLogistica']);
Router::post('/dashboard/cotizaciones/update-precio', [DashboardController::class, 'updateItemPrecio']);
Router::post('/dashboard/cotizaciones/update-cantidad',[DashboardController::class, 'updateItemCantidad']);
Router::post('/dashboard/cotizaciones/eliminar-item', [DashboardController::class, 'eliminarItemAdmin']);
Router::post('/dashboard/cotizaciones/agregar-item',  [DashboardController::class, 'agregarItemAdmin']);
Router::post('/dashboard/cotizaciones/actualizar-comercial', [DashboardController::class, 'actualizarComercial']);
Router::post('/dashboard/cotizaciones/emitir',        [DashboardController::class, 'prepararPedidoAdmin']);
Router::post('/dashboard/cotizaciones/rechazar',      [DashboardController::class, 'anularPedidoAdmin']);
Router::post('/dashboard/cotizaciones/aprobar',       [DashboardController::class, 'generarFacturaAdmin']);

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

Router::get('/pedido/actual',             [CotizacionClienteController::class, 'actual']);
Router::post('/pedido/agregar',           [CotizacionClienteController::class, 'agregar']);
Router::post('/pedido/item/actualizar',   [CotizacionClienteController::class, 'actualizarItem']);
Router::post('/pedido/item/eliminar',     [CotizacionClienteController::class, 'eliminarItem']);
Router::post('/pedido/enviar',            [CotizacionClienteController::class, 'enviar']);
Router::get('/pedido/exito',              [CotizacionClienteController::class, 'exito']);
Router::get('/mis-pedidos',               [CotizacionClienteController::class, 'historial']);
Router::get('/mis-pedidos/{id}',          [CotizacionClienteController::class, 'detalle']);
Router::get('/pedido/detalle/{id}',       [CotizacionClienteController::class, 'detalle']);
Router::get('/pedido/pdf/{id}',           [CotizacionClienteController::class, 'pdf']);
Router::get('/factura/pdf/{id}',          [CotizacionClienteController::class, 'facturaPdf']);
Router::post('/pedido/enviar_correo/{id}',[CotizacionClienteController::class, 'enviarCorreo']);
Router::get('/pedido/pagar/{id}',         [CotizacionClienteController::class, 'pagar']);
Router::post('/pedido/pagar/{id}',        [CotizacionClienteController::class, 'procesarPago']);

Router::get('/catalogo', [CatalogoController::class, 'index']);
Router::get('/producto/{id}', [CatalogoController::class, 'producto']);
Router::get('/servicio/{id}', [CatalogoController::class, 'servicio']);

Router::get('/', [HomeController::class, 'index']);

Router::dispatch();