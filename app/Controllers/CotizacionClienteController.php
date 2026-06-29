<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Core\Database;
use App\Models\CotizacionesModel;
use App\Models\PagosModel;
use App\Core\TasaBCV;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\Core\Config;

class CotizacionClienteController extends Router
{
    private CotizacionesModel $cotizacionesModel;
    private PagosModel $pagosModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $this->cotizacionesModel = new CotizacionesModel();
        $this->pagosModel = new \App\Models\PagosModel();
    }

    public function actual(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $borrador = $this->cotizacionesModel->getBorradorByUserId($userId);
        
        $detalles = [];
        if ($borrador) {
            $detalles = $this->cotizacionesModel->getDetalles((int)$borrador['id']);
        }

        $this->view('cotizacion/actual', [
            'title' => 'Lista de Cotización',
            'cotizacion' => $borrador,
            'detalles' => $detalles
        ]);
    }

    public function agregar(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $productoId = !empty($_POST['producto_id']) ? (int)$_POST['producto_id'] : null;
        $servicioId = !empty($_POST['servicio_id']) ? (int)$_POST['servicio_id'] : null;
        $cantidad = !empty($_POST['cantidad']) ? (float)$_POST['cantidad'] : 1.0;
        $precio = !empty($_POST['precio']) ? (float)$_POST['precio'] : 0.0;

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (!$productoId && !$servicioId) {
            $this->renderJsonError('Debe especificar un producto o servicio.', $isAjax);
            return;
        }

        // Obtener o crear borrador
        $borrador = $this->cotizacionesModel->getBorradorByUserId($userId);
        if (!$borrador) {
            $cotizacionId = $this->cotizacionesModel->createBorrador($userId);
        } else {
            $cotizacionId = (int)$borrador['id'];
        }

        $res = $this->cotizacionesModel->addItem($cotizacionId, $productoId, $servicioId, $cantidad, $precio);

        if ($res) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Agregado a la lista de cotización.']);
                exit;
            }
            $_SESSION['success_msg'] = 'Item agregado a la solicitud.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $this->renderJsonError('No se pudo agregar el item.', $isAjax);
    }

    public function actualizarItem(): void
    {
        $detalleId = (int)$_POST['detalle_id'];
        $cantidad = (float)$_POST['cantidad'];

        $res = $this->cotizacionesModel->updateItemQuantity($detalleId, $cantidad);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Cantidad actualizada.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar.']);
        }
        exit;
    }

    public function eliminarItem(): void
    {
        $detalleId = (int)$_POST['detalle_id'];

        $res = $this->cotizacionesModel->removeItem($detalleId);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Item eliminado.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar.']);
        }
        exit;
    }

    public function enviar(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $notas = strip_tags(trim($_POST['notas_tecnicas'] ?? ''));
        $tipo_entrega = $_POST['tipo_entrega'] ?? null;
        $direccion_envio = strip_tags(trim($_POST['direccion_envio'] ?? ''));

        $borrador = $this->cotizacionesModel->getBorradorByUserId($userId);
        
        if (!$borrador) {
            $_SESSION['error_msg'] = 'No hay solicitud actual.';
            header('Location: ' . $this->baseUrl() . '/pedido/actual');
            exit;
        }

        $detalles = $this->cotizacionesModel->getDetalles((int)$borrador['id']);
        if (empty($detalles)) {
            $_SESSION['error_msg'] = 'No puede enviar una solicitud vacía.';
            header('Location: ' . $this->baseUrl() . '/pedido/actual');
            exit;
        }

        $res = $this->cotizacionesModel->sendCotizacion((int)$borrador['id'], $notas, $tipo_entrega, $direccion_envio);

        if ($res) {
            $_SESSION['success_msg'] = '¡Solicitud de presupuesto enviada correctamente! Nos comunicaremos pronto.';
            header('Location: ' . $this->baseUrl() . '/pedido/exito');
            exit;
        }

        $_SESSION['error_msg'] = 'Ocurrió un error al enviar la solicitud.';
        header('Location: ' . $this->baseUrl() . '/pedido/actual');
        exit;
    }

    public function exito(): void
    {
        $this->view('cotizacion/exito', [
            'title' => '¡Cotización Enviada!'
        ]);
    }

    public function historial(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $cotizaciones = $this->cotizacionesModel->getHistoryByUserId($userId);

        $this->view('cotizacion/historial', [
            'title' => 'Mis Cotizaciones',
            'cotizaciones' => $cotizaciones
        ]);
    }

    public function detalle(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $cotizacionId = (int)$id;

        if ($userRol === 1) {
            $cotizacion = $this->cotizacionesModel->getByIdAdmin($cotizacionId);
        } else {
            $cotizacion = $this->cotizacionesModel->getById($cotizacionId, $userId);
        }

        if (!$cotizacion) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $detalles = $this->cotizacionesModel->getDetalles($cotizacionId);

        // Si no está facturado todavía, usamos la tasa BCV en vivo
        if ($cotizacion['estado_id'] < 4) {
            $tasaData = \App\Core\TasaBCV::getTasa();
            $cotizacion['tasabcv'] = $tasaData['tasa'];
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCotizacionId($cotizacionId);

        $this->view('cotizacion/detalle', [
            'title'      => 'Detalle de Solicitud #' . $cotizacionId,
            'cotizacion' => $cotizacion,
            'pedido'     => $pedido,
            'detalles'   => $detalles
        ]);
    }

    private function renderJsonError(string $message, bool $isAjax): void
    {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $message]);
            exit;
        }
        
        $_SESSION['error_msg'] = $message;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function generatePdfContent(int $cotizacionId, int $userId, int $userRol): ?string
    {
        if ($userRol === 1) {
            $cotizacion = $this->cotizacionesModel->getByIdAdmin($cotizacionId);
        } else {
            $cotizacion = $this->cotizacionesModel->getById($cotizacionId, $userId);
        }

        if (!$cotizacion) {
            return null;
        }

        $detalles = $this->cotizacionesModel->getDetalles($cotizacionId);

        ob_start();
        require dirname(__DIR__) . '/Views/cotizacion/pdf_template.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function pdf(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $cotizacionId = (int)$id;

        $pdfContent = $this->generatePdfContent($cotizacionId, $userId, $userRol);

        if (!$pdfContent) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Cotizacion_' . $cotizacionId . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo $pdfContent;
        exit;
    }

    private function generateFacturaPdfContent(int $cotizacionId, int $userId, int $userRol): ?string
    {
        if ($userRol === 1) {
            $cotizacion = $this->cotizacionesModel->getByIdAdmin($cotizacionId);
        } else {
            $cotizacion = $this->cotizacionesModel->getById($cotizacionId, $userId);
        }

        if (!$cotizacion) {
            return null;
        }

        $facturasModel = new \App\Models\FacturasModel();
        $factura = $facturasModel->getByCotizacionId($cotizacionId);

        if (!$factura) {
            return null; // No invoice generated yet
        }

        $detalles = $this->cotizacionesModel->getDetalles($cotizacionId);

        ob_start();
        require dirname(__DIR__) . '/Views/cotizacion/factura_pdf.php';
        $html = ob_get_clean();

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function facturaPdf(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $cotizacionId = (int)$id;

        $pdfContent = $this->generateFacturaPdfContent($cotizacionId, $userId, $userRol);

        if (!$pdfContent) {
            $_SESSION['error_msg'] = 'Factura no encontrada o no generada aún.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="factura_' . $cotizacionId . '.pdf"');
        echo $pdfContent;
        exit;
    }


    public function enviarCorreo(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $cotizacionId = (int)$id;

        // Sólo admins
        if ($userRol !== 1) {
            $_SESSION['error_msg'] = 'No tiene permisos para enviar correos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $cotizacion = $this->cotizacionesModel->getByIdAdmin($cotizacionId);
        if (!$cotizacion) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $pdfContent = $this->generatePdfContent($cotizacionId, $userId, $userRol);

        try {
            $mail = \App\Core\MailerService::make();

            $mail->addAddress($cotizacion['cliente_email'], $cotizacion['cliente_nombre']);

            $mail->isHTML(true);
            $mail->Subject = 'Cotización InstalFuego #' . str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT);
            $mail->Body    = '<p>Estimado(a) ' . htmlspecialchars($cotizacion['cliente_nombre']) . ',</p>'
                           . '<p>Adjunto a este correo encontrará la cotización solicitada.</p>'
                           . '<p>Saludos cordiales,<br>El equipo de InstalFuego C.A.</p>';

            $mail->addStringAttachment($pdfContent, 'Cotizacion_' . $cotizacionId . '.pdf');

            $mail->send();
            $_SESSION['success_msg'] = 'Presupuesto/Pedido enviado por correo exitosamente.';
        } catch (PHPMailerException $e) {
            $errorInfo = isset($mail) ? $mail->ErrorInfo : $e->getMessage();
            $_SESSION['error_msg'] = 'No se pudo enviar el correo. Error: ' . $errorInfo;
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }

    public function pagar(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $cotizacionId = (int)$id;

        $cotizacion = $this->cotizacionesModel->getById($cotizacionId, $userId);
        
        if (!$cotizacion || $cotizacion['usuario_id'] != $userId) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        // Permitir pago si estado es listo_para_pago (3) o facturado (4)
        if (!in_array($cotizacion['estado_id'], [3, 4])) {
            $_SESSION['error_msg'] = 'Este presupuesto no está disponible para pago.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $cotizacionId);
            exit;
        }

        // Si no está facturado todavía, usamos la tasa BCV en vivo
        if ($cotizacion['estado_id'] < 4) {
            $tasaData = \App\Core\TasaBCV::getTasa();
            $cotizacion['tasabcv'] = $tasaData['tasa'];
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCotizacionId($cotizacionId);

        if (!$pedido) {
            // Crear pedido automáticamente al intentar pagar
            $pedidoId = $pedidosModel->createFromCotizacion(
                $cotizacionId, 
                $userId, 
                (float)$cotizacion['total'], 
                (float)$cotizacion['subtotal'], 
                (float)$cotizacion['impuestos'], 
                (float)$cotizacion['descuento']
            );
            $pedido = $pedidosModel->getById($pedidoId);
        }

        $detalles = $this->cotizacionesModel->getDetalles($cotizacionId);
        $metodos = $this->cotizacionesModel->getMetodosPago();

        $this->view('cotizacion/pagar', [
            'cotizacion' => $cotizacion,
            'pedido'     => $pedido,
            'detalles'   => $detalles,
            'subtotal'   => $cotizacion['subtotal'],
            'descuento'  => $cotizacion['descuento'],
            'iva'        => $cotizacion['impuestos'],
            'totalFinal' => $cotizacion['total'],
            'metodos'    => $metodos
        ]);
    }

    public function procesarPago(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $cotizacionId = (int)$id;

        $cotizacion = $this->cotizacionesModel->getById($cotizacionId, $userId);
        if (!$cotizacion || $cotizacion['usuario_id'] != $userId) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCotizacionId($cotizacionId);

        if (!$pedido) {
            $_SESSION['error_msg'] = 'El pedido no ha sido inicializado. Visite la página de pago primero.';
            header('Location: ' . $this->baseUrl() . '/pedido/pagar/' . $cotizacionId);
            exit;
        }

        $comprobante_url = null;
        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['comprobante']['tmp_name'];
            $name = basename($_FILES['comprobante']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
                $newName = 'pago_' . $pedido['id'] . '_' . time() . '.' . $ext;
                $uploadDir = dirname(__DIR__, 2) . '/public/img/pagos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    $comprobante_url = '/img/pagos/' . $newName;
                }
            }
        }

        if (!$comprobante_url) {
            $_SESSION['error_msg'] = 'Debe subir un comprobante válido (JPG, PNG o PDF).';
            header('Location: ' . $this->baseUrl() . '/pedido/pagar/' . $cotizacionId);
            exit;
        }

        $data = [
            'pedido_id'        => $pedido['id'],
            'metodo_pago_id'   => (int)$_POST['metodo_pago_id'],
            'monto'            => (float)$_POST['monto'],
            'moneda'           => $_POST['moneda'] ?? 'VES',
            'referencia'       => strip_tags(trim($_POST['referencia'] ?? '')),
            'banco_origen'     => strip_tags(trim($_POST['banco_origen'] ?? '')),
            'telefono_pagador' => strip_tags(trim($_POST['telefono_pagador'] ?? '')),
            'comprobante_url'  => $comprobante_url
        ];

        if ($this->pagosModel->createPago($data)) {
            $_SESSION['success_msg'] = '¡Pago reportado exitosamente! Será validado a la brevedad.';
        } else {
            $_SESSION['error_msg'] = 'Ocurrió un error al reportar el pago.';
        }

        header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $cotizacionId);
        exit;
    }
}
