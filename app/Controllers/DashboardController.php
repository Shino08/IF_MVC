<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;
use App\Models\ProductsModel;

class DashboardController extends Router
{
    // ── Middleware: verificar sesión ──────────────────────────────────
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }
    }

    // ── Dashboard principal ───────────────────────────────────────────
    public function index(): void
    {
        $this->requireAuth();
        $this->view('dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }

    // ── Productos ─────────────────────────────────────────────────────
    public function productos(): void
    {

        $productos = (new ProductsModel())->getAllProductsWithCategory();
        $this->requireAuth();
        $this->view('dashboard/productos', [
            'title' => 'Gestión de Productos',
            'productos' => $productos
        ]);


    }

    public function agregarProducto(): void
    {
        $this->requireAuth();
        $categorias = (new CategoriasModel())->getAll();
        $this->view('dashboard/agregarProducto', [
            'title' => 'Agregar Producto',
            'categorias' => $categorias // <-- Aquí inyectamos los datos
        ]);
    }

    public function editarProducto(int $id): void
    {
        $this->requireAuth();
        // En el futuro: $producto = (new Producto())->find($id);
        $this->view('dashboard/editarProducto', [
            'title'    => 'Editar Producto',
            'producto' => ['id' => $id, 'nombre' => '', 'categoria_id' => 1, 'descripcion' => '', 'imagen' => ''],
        ]);
    }

    // ── Categorías ────────────────────────────────────────────────────
    public function categorias(): void
    {
        $this->requireAuth();
        $this->view('dashboard/categoria', [
            'title' => 'Gestión de Categorías',
        ]);
    }

    // ── Cotizaciones ──────────────────────────────────────────────────
    public function cotizaciones(): void
    {
        $this->requireAuth();
        $this->view('dashboard/soliCotizacion', [
            'title' => 'Solicitudes de Cotización',
        ]);
    }

    // ── Servicios ─────────────────────────────────────────────────────
    public function servicios(): void
    {
        $this->requireAuth();
        $this->view('dashboard/servicios', [
            'title' => 'Servicios',
        ]);
    }

    // ── Reportes ──────────────────────────────────────────────────────
    public function reportes(): void
    {
        $this->requireAuth();
        $this->view('dashboard/reportes', [
            'title' => 'Reportes',
        ]);
    }

    // ── Helper privado ────────────────────────────────────────────────
    private function baseUrl(): string
    {
        return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    }
}
