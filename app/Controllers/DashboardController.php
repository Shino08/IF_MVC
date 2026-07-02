<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;
use App\Models\ProductsModel;
use App\Models\ServiciosModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Core\TasaBCV;

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

        // ── Cotizaciones stats ─────────────────────────────────────
        $db = \App\Core\Database::getInstance();
        $cotTotal = 0; $cotPendientes = 0; $cotEnviadas = 0;
        try {
            $stmt = $db->query("SELECT estado_id FROM cotizaciones WHERE estado_id != 1");
            $all = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $cotTotal = count($all);
            foreach ($all as $r) {
                if ($r['estado_id'] == 2) $cotPendientes++;
                if ($r['estado_id'] == 3) $cotEnviadas++;
            }
        } catch (\Exception $e) {
            error_log('Dashboard cotizaciones stats: ' . $e->getMessage());
        }

        $this->view('dashboard/index', [
            'title'            => 'Dashboard',
            'totalProductos'   => $totalProductos,
            'totalServicios'   => $totalServicios,
            'totalCategorias'  => $totalCategorias,
            'sinStock'         => $sinStock,
            'catMap'           => $catMap,
            'ultimosProductos' => $ultimosProductos,
            'ultimosServicios' => $ultimosServicios,
            'cotTotal'         => $cotTotal,
            'cotPendientes'    => $cotPendientes,
            'cotEnviadas'      => $cotEnviadas,
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

        // Si no está facturado todavía, usamos la tasa BCV en vivo
        if ($cotizacion['estado_id'] < 4) {
            $tasaData = \App\Core\TasaBCV::getTasa();
            $cotizacion['tasabcv'] = $tasaData['tasa'];
        }

        $detalles = $cotizacionesModel->getDetalles($id);
        $metodosPago = $cotizacionesModel->getMetodosPago();
        $productos = (new ProductsModel())->getAllProductsWithCategory();
        $servicios = (new ServiciosModel())->getAll();
        
        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCotizacionId($id);

        $pagos = [];
        if ($pedido) {
            $pagosModel = new \App\Models\PagosModel();
            $pagos = $pagosModel->getPagosByPedido((int)$pedido['id']);
        }

        $this->view('dashboard/detalle-solicitud', [
            'title'       => 'Detalle de Solicitud #' . $id,
            'cotizacion'  => $cotizacion,
            'pedido'      => $pedido,
            'detalles'    => $detalles,
            'metodosPago' => $metodosPago,
            'productos'   => $productos,
            'servicios'   => $servicios,
            'pagos'       => $pagos
        ]);
    }


    public function actualizarLogistica(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $estadoPedido = strip_tags(trim($_POST['estado_pedido'] ?? ''));

        $allowed = ['procesando', 'despachado', 'entregado', 'cancelado'];

        if ($cotizacionId > 0 && in_array($estadoPedido, $allowed)) {
            $db = \App\Core\Database::getInstance();

            // Actualizar el estado en la tabla pedidos (fuente de verdad del timeline)
            $extra = '';
            if ($estadoPedido === 'despachado') {
                $extra = ', fecha_despacho = CURRENT_TIMESTAMP';
            } elseif ($estadoPedido === 'entregado') {
                $extra = ', fecha_entrega = CURRENT_TIMESTAMP';
            }

            $stmt = $db->prepare("UPDATE pedidos SET estado_pedido = :estado{$extra} WHERE cotizacion_id = :cid");
            $stmt->execute([':estado' => $estadoPedido, ':cid' => $cotizacionId]);

            $_SESSION['success_msg'] = 'Estado del pedido actualizado correctamente.';
        } else {
            $_SESSION['error_msg'] = 'Estado no válido.';
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
        
        if (isset($_POST['aplica_iva'])) 
            $data['aplica_iva'] = (int)$_POST['aplica_iva'];
        if (isset($_POST['tasa_iva'])) 
            $data['tasa_iva'] = (float)$_POST['tasa_iva'];
        if (isset($_POST['motivo_exento'])) 
            $data['motivo_exento'] = strip_tags(trim($_POST['motivo_exento']));
            
        if (isset($_POST['descuento'])) 
            $data['descuento'] = (float)$_POST['descuento'];
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
        if (isset($_POST['costo_envio'])) 
            $data['costo_envio'] = (float)$_POST['costo_envio'];
        if (isset($_POST['ubicacion'])) 
            $data['ubicacion'] = strip_tags(trim($_POST['ubicacion']));
        if (isset($_POST['fecha_tentativa'])) 
            $data['fecha_tentativa'] = strip_tags(trim($_POST['fecha_tentativa'])) ?: null;
        if (isset($_POST['responsable_nombre'])) 
            $data['responsable_nombre'] = strip_tags(trim($_POST['responsable_nombre']));
        if (isset($_POST['responsable_telefono'])) 
            $data['responsable_telefono'] = strip_tags(trim($_POST['responsable_telefono']));
        if (isset($_POST['observaciones_tecnicas'])) 
            $data['observaciones_tecnicas'] = strip_tags(trim($_POST['observaciones_tecnicas']));

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

        // Obtener tasa BCV del momento
        $tasaData = TasaBCV::getTasa();
        $tasabcv  = $tasaData['tasa'];

        // Calcular monto en USD a partir del total en Bs y validar envío e IVA
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare('SELECT total, tipo_entrega, costo_envio, aplica_iva FROM cotizaciones WHERE id = :id');
        $stmt->execute([':id' => $cotizacionId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION['error_msg'] = 'Cotización no encontrada.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        if ($row['aplica_iva'] === null) {
            $_SESSION['error_msg'] = 'Debe definir si la cotización aplica IVA o es Exenta antes de emitir. (Pestaña Logística / Configuración Comercial)';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        if ($row['tipo_entrega'] === 'domicilio' && (float)$row['costo_envio'] <= 0) {
            $_SESSION['error_msg'] = 'Para entregas a domicilio, debe cargar el Costo de Envío antes de emitir. (Pestaña Logística)';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $totalUsd = (float)$row['total'];
        $montoBs = null;
        if ($tasabcv > 0 && $totalUsd > 0) {
            $montoBs = round($totalUsd * $tasabcv, 2);
        }

        $model = new \App\Models\CotizacionesModel();
        $res = $model->emitirCotizacion($cotizacionId, $notasCliente, $tasabcv, $montoBs);

        if ($res) {
            $_SESSION['success_msg'] = 'Cotización emitida exitosamente. Estado cambiado a "Enviada".';
        } else {
            $_SESSION['error_msg'] = 'No se pudo emitir la cotización. Verifique que esté en estado Pendiente.';
        }

        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }


    public function anularPedidoAdmin(): void
    {
        $this->requireAuth();
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);

        if (!$cotizacionId) {
            $_SESSION['error_msg'] = 'ID de pedido no válido.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $db = \App\Core\Database::getInstance();
        // Puede anularse desde pendiente de revision (2) o listo para pago (3)
        $stmt = $db->prepare("UPDATE cotizaciones SET estado_id = 5 WHERE id = :id AND estado_id IN (2, 3)");
        $stmt->execute([':id' => $cotizacionId]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = 'Pedido anulado exitosamente.';
        } else {
            $_SESSION['error_msg'] = 'No se pudo anular. Verifique el estado actual.';
        }

        header('Location: ' . $this->baseUrl() . '/dashboard/detalle-solicitud/' . $cotizacionId);
        exit;
    }

    public function validarPago(): void
    {
        $this->requireAuth();
        $pagoId = (int)($_POST['pago_id'] ?? 0);
        $cotizacionId = (int)($_POST['cotizacion_id'] ?? 0);
        $observacionesAdmin = strip_tags(trim($_POST['observaciones_admin'] ?? ''));
        $accion = $_POST['accion'] ?? ''; // 'validado' o 'rechazado'

        if (!$pagoId || !$cotizacionId || !in_array($accion, ['validado', 'rechazado'])) {
            $_SESSION['error_msg'] = 'Parámetros no válidos.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $pagosModel = new \App\Models\PagosModel();
        $ok = $pagosModel->validarPago($pagoId, $accion, $observacionesAdmin);

        if ($ok) {
            if ($accion === 'validado') {
                $pedidoId = $pagosModel->getPedidoIdByPagoId($pagoId);
                
                if ($pedidoId) {
                    // Generar factura
                    $facturasModel = new \App\Models\FacturasModel();
                    $facturasModel->crearFactura((int)$pedidoId);

                    // Enviar correo
                    $pedidosModel = new \App\Models\PedidosModel();
                    $pedido = $pedidosModel->getById((int)$pedidoId);

                    if ($pedido) {
                        \App\Core\MailerService::enviarCorreoPagoValidado($pedido);
                    }
                }
            }
            $_SESSION['success_msg'] = 'Pago ' . ($accion === 'validado' ? 'validado y factura generada' : 'rechazado') . ' exitosamente.';
        } else {
            $_SESSION['error_msg'] = 'Ocurrió un error al procesar el pago.';
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
            'imagenes'   => $model->getImages($id),
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

        switch ($tipo) {
            case 'pedidos':
                $sql = "SELECT p.id, p.fecha_creacion as fecha_solicitud, CONCAT(u.nombre, ' ', u.apellido) as cliente, 
                               p.estado_pedido as estado, p.total_pedido as total
                        FROM pedidos p
                        JOIN usuarios u ON p.usuario_id = u.id
                        WHERE DATE(p.fecha_creacion) BETWEEN :inicio AND :fin";
                
                $params = [':inicio' => $fechaInicio, ':fin' => $fechaFin];
                
                if ($estado !== '') {
                    $sql .= " AND p.estado_pedido = :estado";
                    $params[':estado'] = $estado;
                }
                
                $sql .= " ORDER BY p.fecha_creacion DESC";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($data as $row) {
                    $totales['estimado'] += $row['total'];
                    $totales['total_cotizaciones']++;
                }
                break;
                
            case 'pagos':
                $sql = "SELECT p.id, p.fecha_reporte as fecha_solicitud, 
                               CONCAT('Pedido #', p.pedido_id) as cliente,
                               p.estado as estado, p.monto as total
                        FROM pagos p
                        WHERE DATE(p.fecha_reporte) BETWEEN :inicio AND :fin
                        ORDER BY p.fecha_reporte DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':inicio' => $fechaInicio, ':fin' => $fechaFin]);
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                foreach ($data as $row) {
                    $totales['estimado'] += $row['total'];
                    $totales['total_cotizaciones']++;
                }
                break;
                
            case 'facturas':
                $sql = "SELECT f.id, f.fecha_emision as fecha_solicitud, CONCAT(u.nombre, ' ', u.apellido) as cliente,
                               'emitida' as estado, f.total as total
                        FROM facturas f
                        JOIN pedidos p ON f.pedido_id = p.id
                        JOIN usuarios u ON p.usuario_id = u.id
                        WHERE DATE(f.fecha_emision) BETWEEN :inicio AND :fin
                        ORDER BY f.fecha_emision DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':inicio' => $fechaInicio, ':fin' => $fechaFin]);
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                foreach ($data as $row) {
                    $totales['estimado'] += $row['total'];
                    $totales['total_cotizaciones']++;
                }
                break;
                
            case 'productos_solicitados':
                $sql = "SELECT 'Producto' as tipo_item, pr.nombre, SUM(cd.cantidad) as total_solicitado
                        FROM cotizacion_detalles cd
                        JOIN productos pr ON cd.producto_id = pr.id
                        JOIN pedidos p ON cd.cotizacion_id = p.cotizacion_id
                        WHERE DATE(p.fecha_creacion) BETWEEN :inicio AND :fin
                        GROUP BY pr.id
                        ORDER BY total_solicitado DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':inicio' => $fechaInicio, ':fin' => $fechaFin]);
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
                
            case 'servicios_solicitados':
                $sql = "SELECT 'Servicio' as tipo_item, s.nombre, SUM(cd.cantidad) as total_solicitado
                        FROM cotizacion_detalles cd
                        JOIN servicios s ON cd.servicio_id = s.id
                        JOIN pedidos p ON cd.cotizacion_id = p.cotizacion_id
                        WHERE DATE(p.fecha_creacion) BETWEEN :inicio AND :fin
                        GROUP BY s.id
                        ORDER BY total_solicitado DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':inicio' => $fechaInicio, ':fin' => $fechaFin]);
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
        }

        if ($exportar === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_' . $tipo . '.csv"');
            $output = fopen('php://output', 'w');
            fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            if (in_array($tipo, ['productos_solicitados', 'servicios_solicitados'])) {
                fputcsv($output, ['Tipo', 'Nombre', 'Total Solicitado']);
                foreach ($data as $row) {
                    fputcsv($output, [$row['tipo_item'], $row['nombre'], $row['total_solicitado']]);
                }
            } else {
                fputcsv($output, ['ID', 'Fecha', 'Cliente/Ref', 'Estado', 'Total']);
                foreach ($data as $row) {
                    fputcsv($output, [$row['id'], $row['fecha_solicitud'], $row['cliente'], $row['estado'], $row['total']]);
                }
            }
            fclose($output);
            exit;
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
