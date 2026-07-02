<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\UsersModel;
use App\Models\ProductsModel;

class HomeController extends Router
{
    public function index(): void
    {
        // session_destroy();
        $sesionIniciada = isset($_SESSION['user_id']);
        $nombreUsuario   = $_SESSION['user_name'] ?? 'Visitante';

        $productsModel = new ProductsModel();
        $serviciosModel = new \App\Models\ServiciosModel();
        $allProducts = $productsModel->getAllProductsWithCategory();
        $destacados = array_slice($allProducts, 0, 4);
        $ultimosProductos = array_slice($allProducts, 0, 5);
        $ultimosServicios = array_slice($serviciosModel->getAll(), 0, 5);

        // Unificar últimos (productos + servicios)
        $ultimos = array_merge(
            array_map(fn($p) => ['tipo' => 'producto', 'id' => $p['id'], 'nombre' => $p['nombre'], 'imagen_principal' => $p['imagen_principal'] ?? '', 'sku' => $p['sku'] ?? ''], $ultimosProductos),
            array_map(fn($s) => ['tipo' => 'servicio', 'id' => $s['id'], 'nombre' => $s['nombre'], 'imagen_principal' => $s['imagen_principal'] ?? '', 'sku' => $s['codigo'] ?? ''], $ultimosServicios)
        );
        // Ordenar por ID descendente (más reciente primero) y tomar 5
        usort($ultimos, fn($a, $b) => $b['id'] - $a['id']);
        $ultimos = array_slice($ultimos, 0, 5);

        $data = [
            'title'     => 'InstalFuego — Sistemas de Seguridad Contra Incendios',
            'message'   => $sesionIniciada
                ? "¡Hola, $nombreUsuario! Has iniciado sesión correctamente."
                : 'Bienvenido a InstalFuego, tu proveedor de sistemas de seguridad contra incendios.',
            'logged_in' => $sesionIniciada,
            'destacados'=> $destacados,
            'ultimos'   => $ultimos,
            'servicios' => array_slice($serviciosModel->getAll(), 0, 4),
        ];

        $this->view('home', $data);
    }
}
