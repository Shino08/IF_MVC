<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;
use App\Models\ProductsModel;
use App\Models\ServiciosModel;

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

        $productModel  = new ProductsModel();
        $servicioModel = new ServiciosModel();
        $catModel      = new CategoriasModel();

        // ── Datos reales ─────────────────────────────────────────────
        $todosProductos  = $productModel->getAllProductsWithCategory();
        $todosServicios  = $servicioModel->getAll();
        $todasCategorias = $catModel->getAll();

        // KPIs
        $totalProductos  = count($todosProductos);
        $totalServicios  = count($todosServicios);
        $totalCategorias = count($todasCategorias);
        $sinStock        = count(array_filter($todosProductos, fn($p) => (int)$p['existencia'] === 0));

        // Productos agrupados por categoría
        $catMap = [];
        foreach ($todosProductos as $p) {
            $catNom = $p['categoria_nombre'] ?? 'Sin Categoría';
            $catMap[$catNom] = ($catMap[$catNom] ?? 0) + 1;
        }
        arsort($catMap); // mayor a menor

        // Últimos 5 productos agregados
        $ultimosProductos = array_slice($todosProductos, 0, 5);

        // Últimos 5 servicios agregados
        $ultimosServicios = array_slice($todosServicios, 0, 5);

        $this->view('dashboard/index', [
            'title'            => 'Dashboard',
            'totalProductos'   => $totalProductos,
            'totalServicios'   => $totalServicios,
            'totalCategorias'  => $totalCategorias,
            'sinStock'         => $sinStock,
            'catMap'           => $catMap,
            'ultimosProductos' => $ultimosProductos,
            'ultimosServicios' => $ultimosServicios,
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
        $productModel = new ProductsModel();
        $producto     = $productModel->findById($id);

        if (!$producto) {
            header('Location: ' . $this->baseUrl() . '/dashboard/productos');
            exit;
        }

        $categorias = (new CategoriasModel())->getAll();
        $imagenes   = $productModel->getImages($id);

        $this->view('dashboard/agregarProducto', [
            'title'      => 'Editar Producto',
            'modo'       => 'editar',
            'producto'   => $producto,
            'imagenes'   => $imagenes,
            'categorias' => $categorias,
        ]);
    }

    // ── Categorías ────────────────────────────────────────────────────
    public function categorias(): void
    {
        $categorias = (new CategoriasModel())->getAll();
        $this->requireAuth();
        $this->view('dashboard/categoria', [
            'title' => 'Gestión de Categorías',
            'categorias' => $categorias
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
        $model = new ServiciosModel();
        $this->view('dashboard/servicios', [
            'title'      => 'Gestión de Servicios',
            'servicios'  => $model->getAll(),
            'categorias' => (new CategoriasModel())->getAll(),
        ]);
    }

    public function agregarServicio(): void
    {
        $this->requireAuth();
        $model = new ServiciosModel();
        $this->view('dashboard/agregarServicio', [
            'title'      => 'Agregar Servicio',
            'categorias' => (new CategoriasModel())->getAll(),
            'tiposCobro' => $model->getTiposCobro(),
        ]);
    }

    public function editarServicio(int $id): void
    {
        $this->requireAuth();
        $model    = new ServiciosModel();
        $servicio = $model->findById($id);

        if (!$servicio) {
            header('Location: ' . $this->baseUrl() . '/dashboard/servicios');
            exit;
        }

        $this->view('dashboard/agregarServicio', [
            'title'      => 'Editar Servicio',
            'modo'       => 'editar',
            'servicio'   => $servicio,
            'categorias' => (new CategoriasModel())->getAll(),
            'tiposCobro' => $model->getTiposCobro(),
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
