<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;
use App\Models\ProductsModel;
use App\Models\ServiciosModel;

class DashboardController extends Router
{
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }
    }

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
            'categorias' => $categorias
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

    public function categorias(): void
    {
        $categorias = (new CategoriasModel())->getAll();
        $this->requireAuth();
        $this->view('dashboard/categoria', [
            'title' => 'Gestión de Categorías',
            'categorias' => $categorias
        ]);
    }

    public function cotizaciones(): void
    {
        $this->requireAuth();
        $cotizacionesModel = new \App\Models\CotizacionesModel();
        $solicitudes = $cotizacionesModel->getAllAdmin();
        $this->view('dashboard/soliCotizacion', [
            'title' => 'Solicitudes de Cotización',
            'solicitudes' => $solicitudes
        ]);
    }

    public function detalleSolicitud(int $id): void
    {
        $this->requireAuth();
        $cotizacionesModel = new \App\Models\CotizacionesModel();
        $cotizacion = $cotizacionesModel->getByIdAdmin($id);

        if (!$cotizacion) {
            header('Location: ' . $this->baseUrl() . '/dashboard/cotizaciones');
            exit;
        }

        $detalles = $cotizacionesModel->getDetalles($id);

        $this->view('dashboard/detalle-solicitud', [
            'title' => 'Detalle de Solicitud #' . $id,
            'cotizacion' => $cotizacion,
            'detalles' => $detalles
        ]);
    }

    public function procesarCotizacion(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $accion = $_POST['accion'] ?? '';

        if ($cotizacionId > 0 && in_array($accion, ['aceptar', 'rechazar'])) {
            $estadoId = ($accion === 'aceptar') ? 3 : 4; // 3: Aprobada/Procesada, 4: Rechazada
            
            // Lógica directa para actualizar
            $db = \App\Core\Database::getInstance();
            $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = :estado WHERE id = :id");
            $stmt->execute([':estado' => $estadoId, ':id' => $cotizacionId]);
            
            $_SESSION['success_msg'] = 'Cotización actualizada exitosamente.';
        }
        
        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }

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

    public function reportes(): void
    {
        $this->requireAuth();
        $this->view('dashboard/reportes', [
            'title' => 'Reportes',
        ]);
    }

    private function baseUrl(): string
    {
        return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    }
}
