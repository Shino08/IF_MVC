<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;
use App\Models\ProductsModel;
use App\Models\ServiciosModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class DashboardController extends Router
{
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $rolId = (int)($_SESSION['rol_id'] ?? 0);
        // Sólo admin (1) y gerente_operaciones (3) pueden acceder al dashboard
        if (!in_array($rolId, [1, 3], true)) {
            header('Location: ' . $this->baseUrl() . '/');
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
        $metodosPago = $cotizacionesModel->getMetodosPago();
        $productos = (new ProductsModel())->getAllProductsWithCategory();
        $servicios = (new ServiciosModel())->getAll();

        $this->view('dashboard/detalle-solicitud', [
            'title' => 'Detalle de Solicitud #' . $id,
            'cotizacion' => $cotizacion,
            'detalles' => $detalles,
            'metodosPago' => $metodosPago,
            'productos' => $productos,
            'servicios' => $servicios
        ]);
    }

    public function procesarCotizacion(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $accion = $_POST['accion'] ?? '';

        if ($cotizacionId > 0 && in_array($accion, ['aceptar', 'rechazar'])) {
            $db = \App\Core\Database::getInstance();

            if ($accion === 'aceptar') {
                // De pendiente_revision (2) → enviada (3) con los campos comerciales
                $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = 3 WHERE id = :id AND estado_id = 2");
            } else {
                // De pendiente_revision (2) → rechazada (5)
                $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = 5 WHERE id = :id AND estado_id = 2");
            }
            
            $stmt->execute([':id' => $cotizacionId]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success_msg'] = 'Cotización actualizada exitosamente.';
            } else {
                $_SESSION['error_msg'] = 'No se pudo actualizar la cotización. Verifique el estado actual.';
            }
        }
        
        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }

    public function updateItemPrecio(): void
    {
        $this->requireAuth();
        $detalleId = (int)($_POST['detalle_id'] ?? 0);
        $precio = (float)($_POST['precio_unitario'] ?? 0);

        $model = new \App\Models\CotizacionesModel();
        $res = $model->updateItemPrice($detalleId, $precio);

        header('Content-Type: application/json');
        echo json_encode(['success' => $res]);
        exit;
    }

    public function updateItemCantidad(): void
    {
        $this->requireAuth();
        $detalleId = (int)($_POST['detalle_id'] ?? 0);
        $cantidad = (float)($_POST['cantidad'] ?? 0);

        $model = new \App\Models\CotizacionesModel();
        $res = $model->updateItemQuantity($detalleId, $cantidad);

        header('Content-Type: application/json');
        echo json_encode(['success' => $res]);
        exit;
    }

    public function eliminarItemAdmin(): void
    {
        $this->requireAuth();
        $detalleId = (int)($_POST['detalle_id'] ?? 0);

        $model = new \App\Models\CotizacionesModel();
        $res = $model->removeItem($detalleId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $res]);
        exit;
    }

    public function agregarItemAdmin(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $productoId = !empty($_POST['producto_id']) ? (int)$_POST['producto_id'] : null;
        $servicioId = !empty($_POST['servicio_id']) ? (int)$_POST['servicio_id'] : null;
        $cantidad = (float)($_POST['cantidad'] ?? 1);
        $precio = (float)($_POST['precio_unitario'] ?? 0);

        if (!$cotizacionId || (!$productoId && !$servicioId)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
            exit;
        }

        $model = new \App\Models\CotizacionesModel();
        $res = $model->addItem($cotizacionId, $productoId, $servicioId, $cantidad, $precio);

        header('Content-Type: application/json');
        echo json_encode(['success' => $res]);
        exit;
    }

    public function actualizarComercial(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);

        if (!$cotizacionId) {
            $_SESSION['error_msg'] = 'ID de cotización no válido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $data = [];
        
        if (isset($_POST['fecha_vencimiento'])) 
            $data['fecha_vencimiento'] = $_POST['fecha_vencimiento'] ?: null;
        if (isset($_POST['impuestos'])) 
            $data['impuestos'] = (float)$_POST['impuestos'];
        if (isset($_POST['descuento'])) 
            $data['descuento'] = (float)$_POST['descuento'];
        if (isset($_POST['id_metodo_pago'])) 
            $data['id_metodo_pago'] = !empty($_POST['id_metodo_pago']) ? (int)$_POST['id_metodo_pago'] : null;
        if (isset($_POST['condiciones_pago'])) 
            $data['condiciones_pago'] = strip_tags(trim($_POST['condiciones_pago']));
        if (isset($_POST['notas_internas'])) 
            $data['notas_internas'] = strip_tags(trim($_POST['notas_internas']));
        if (isset($_POST['notas_tecnicas'])) 
            $data['notas_tecnicas'] = strip_tags(trim($_POST['notas_tecnicas']));
        if (isset($_POST['proyecto_referencia'])) 
            $data['proyecto_referencia'] = strip_tags(trim($_POST['proyecto_referencia']));
        if (isset($_POST['direccion_envio'])) 
            $data['direccion_envio'] = strip_tags(trim($_POST['direccion_envio']));
        if (isset($_POST['direccion_facturacion'])) 
            $data['direccion_facturacion'] = strip_tags(trim($_POST['direccion_facturacion']));

        $model = new \App\Models\CotizacionesModel();
        $res = $model->updateComercialFields($cotizacionId, $data);

        if ($res) {
            $_SESSION['success_msg'] = 'Campos comerciales actualizados.';
        } else {
            $_SESSION['error_msg'] = 'Error al actualizar campos comerciales.';
        }

        $paso = (int)($_POST['paso'] ?? 0);
        $redirect = $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId;
        if ($paso >= 1 && $paso <= 3) {
            $redirect .= '?paso=' . $paso;
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function emitirCotizacionAdmin(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $notasCliente = strip_tags(trim($_POST['notas_cliente'] ?? ''));

        if (!$cotizacionId) {
            $_SESSION['error_msg'] = 'ID de cotización no válido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $model = new \App\Models\CotizacionesModel();
        $res = $model->emitirCotizacion($cotizacionId, $notasCliente);

        if ($res) {
            $_SESSION['success_msg'] = 'Cotización emitida exitosamente. Estado cambiado a "Enviada".';
        } else {
            $_SESSION['error_msg'] = 'No se pudo emitir la cotización. Verifique que esté en estado Pendiente.';
        }

        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }

    public function rechazarCotizacionAdmin(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $motivo = strip_tags(trim($_POST['motivo_rechazo'] ?? ''));

        if (!$cotizacionId) {
            $_SESSION['error_msg'] = 'ID de cotización no válido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = 5, notas_internas = CONCAT(COALESCE(notas_internas,''), CHAR(10), 'MOTIVO RECHAZO: ', :motivo) WHERE id = :id AND estado_id IN (2, 3)");
        $stmt->execute([':id' => $cotizacionId, ':motivo' => $motivo]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = 'Cotización rechazada.';
        } else {
            $_SESSION['error_msg'] = 'No se pudo rechazar. Verifique el estado actual.';
        }

        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }

    public function aprobarCotizacionAdmin(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);

        if (!$cotizacionId) {
            $_SESSION['error_msg'] = 'ID de cotización no válido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $db = \App\Core\Database::getInstance();
        // De enviada (3) a aprobada (4) - solo admin puede hacer esto
        $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = 4 WHERE id = :id AND estado_id = 3");
        $stmt->execute([':id' => $cotizacionId]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = 'Cotización aprobada exitosamente.';
        } else {
            $_SESSION['error_msg'] = 'No se pudo aprobar. Verifique que esté en estado "Enviada".';
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
        
        $db = \App\Core\Database::getInstance();
        $tipo = $_GET['tipo'] ?? 'cotizaciones';
        $estado = $_GET['estado'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-t');
        $exportar = $_GET['exportar'] ?? '';

        $data = [];
        $totales = [
            'estimado' => 0,
            'procesado' => 0,
            'pendiente' => 0,
            'total_cotizaciones' => 0,
            'cotizaciones_procesadas' => 0
        ];

        if ($tipo === 'cotizaciones') {
            $sql = "SELECT c.id, c.fecha_solicitud, CONCAT(u.nombre, ' ', u.apellido) as cliente, 
                           e.nombre as estado, c.total, c.estado_id
                    FROM cotizaciones c
                    JOIN usuarios u ON c.usuario_id = u.id
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    WHERE c.estado_id != 1 AND DATE(c.fecha_solicitud) BETWEEN :inicio AND :fin";
            
            $params = [':inicio' => $fechaInicio, ':fin' => $fechaFin];
            
            if ($estado !== '') {
                $sql .= " AND c.estado_id = :estado";
                $params[':estado'] = $estado;
            }
            
            $sql .= " ORDER BY c.fecha_solicitud DESC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $row) {
                $totales['estimado'] += $row['total'];
                $totales['total_cotizaciones']++;
                if ($row['estado_id'] == 3) { // 3: Procesada/Aprobada
                    $totales['procesado'] += $row['total'];
                    $totales['cotizaciones_procesadas']++;
                }
                if ($row['estado_id'] == 2) { // 2: Pendiente
                    $totales['pendiente'] += $row['total'];
                }
            }

            if ($exportar === 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="reporte_cotizaciones.csv"');
                $output = fopen('php://output', 'w');
                fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
                fputcsv($output, ['ID', 'Fecha', 'Cliente', 'Estado', 'Total']);
                foreach ($data as $row) {
                    fputcsv($output, [$row['id'], $row['fecha_solicitud'], $row['cliente'], $row['estado'], $row['total']]);
                }
                fclose($output);
                exit;
            }
        } elseif ($tipo === 'mas_solicitados') {
            $sql = "SELECT 'Producto' as tipo_item, p.nombre, SUM(cd.cantidad) as total_solicitado
                    FROM cotizacion_detalles cd
                    JOIN productos p ON cd.producto_id = p.id
                    JOIN cotizaciones c ON cd.cotizacion_id = c.id
                    WHERE c.estado_id != 1 AND DATE(c.fecha_solicitud) BETWEEN :inicio AND :fin
                    GROUP BY p.id
                    UNION ALL
                    SELECT 'Servicio' as tipo_item, s.nombre, SUM(cd.cantidad) as total_solicitado
                    FROM cotizacion_detalles cd
                    JOIN servicios s ON cd.servicio_id = s.id
                    JOIN cotizaciones c ON cd.cotizacion_id = c.id
                    WHERE c.estado_id != 1 AND DATE(c.fecha_solicitud) BETWEEN :inicio AND :fin
                    GROUP BY s.id
                    ORDER BY total_solicitado DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([':inicio' => $fechaInicio, ':fin' => $fechaFin]);
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($exportar === 'csv') {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="reporte_mas_solicitados.csv"');
                $output = fopen('php://output', 'w');
                fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
                fputcsv($output, ['Tipo', 'Nombre', 'Total Solicitado']);
                foreach ($data as $row) {
                    fputcsv($output, [$row['tipo_item'], $row['nombre'], $row['total_solicitado']]);
                }
                fclose($output);
                exit;
            }
        }

        // ── PDF export ──────────────────────────────────────────
        if ($exportar === 'pdf') {
            ob_start();
        }

        $this->view('dashboard/reportes', [
            'title' => 'Reportes',
            'tipo' => $tipo,
            'estado' => $estado,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'data' => $data,
            'totales' => $totales,
            'exportar' => $exportar
        ]);

        if ($exportar === 'pdf') {
            $html = ob_get_clean();

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'reporte_' . $tipo . '_' . date('Y-m-d') . '.pdf';
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            echo $dompdf->output();
            exit;
        }
    }

    private function baseUrl(): string
    {
        return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    }
}
