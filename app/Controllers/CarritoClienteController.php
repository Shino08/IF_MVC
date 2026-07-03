<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Core\Database;
use App\Models\CarritosModel;
use App\Models\PagosModel;
use App\Core\TasaBCV;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\Core\Config;

class CarritoClienteController extends Router
{
    private CarritosModel $carritosModel;
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

        $this->carritosModel = new CarritosModel();
        $this->pagosModel = new \App\Models\PagosModel();
    }

    public function actual(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $borrador = $this->carritosModel->getBorradorByUserId($userId);
        
        $detalles = [];
        if ($borrador) {
            $detalles = $this->carritosModel->getDetalles((int)$borrador['id']);
        }

        $this->view('carrito/actual', [
            'title' => 'Lista de Cotización',
            'carrito' => $borrador,
            'detalles' => $detalles
        ]);
    }

    public function agregar(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $productoId = !empty($_POST['producto_id']) ? (int)$_POST['producto_id'] : null;
        $servicioId = !empty($_POST['servicio_id']) ? (int)$_POST['servicio_id'] : null;
        $cantidad = !empty($_POST['cantidad']) ? (float)$_POST['cantidad'] : 1.0;
        
        $precio = 0.0;
        if ($productoId) {
            $productsModel = new \App\Models\ProductsModel();
            $producto = $productsModel->findById($productoId);
            if ($producto) {
                $precio = (float)($producto['precio'] ?? 0.0);
            }
        } elseif ($servicioId) {
            $serviciosModel = new \App\Models\ServiciosModel();
            $servicio = $serviciosModel->findById($servicioId);
            if ($servicio) {
                $precio = (float)($servicio['precio_referencial'] ?? 0.0);
            }
        }

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (!$productoId && !$servicioId) {
            $this->renderJsonError('Debe especificar un producto o servicio.', $isAjax);
            return;
        }

        // Obtener o crear borrador
        $borrador = $this->carritosModel->getBorradorByUserId($userId);
        if (!$borrador) {
            $carritoId = $this->carritosModel->createBorrador($userId);
        } else {
            $carritoId = (int)$borrador['id'];
        }

        $res = $this->carritosModel->addItem($carritoId, $productoId, $servicioId, $cantidad, $precio);

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

        $res = $this->carritosModel->updateItemQuantity($detalleId, $cantidad);

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

        $res = $this->carritosModel->removeItem($detalleId);

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

        // Consolidar dirección enriquecida
        if ($tipo_entrega === 'domicilio') {
            $partes = array_filter([
                strip_tags(trim($_POST['estado_envio']    ?? '')),
                strip_tags(trim($_POST['municipio_envio'] ?? '')),
                strip_tags(trim($_POST['direccion_envio'] ?? '')),
            ]);
            $referencia = strip_tags(trim($_POST['referencia_envio'] ?? ''));
            if ($referencia) {
                $partes[] = 'Ref: ' . $referencia;
            }
            $direccion_envio = implode(', ', $partes);
        } else {
            $direccion_envio = '';
        }

        $borrador = $this->carritosModel->getBorradorByUserId($userId);

        if (!$borrador) {
            $_SESSION['error_msg'] = 'No hay solicitud actual.';
            header('Location: ' . $this->baseUrl() . '/pedido/actual');
            exit;
        }

        $detalles = $this->carritosModel->getDetalles((int)$borrador['id']);
        if (empty($detalles)) {
            $_SESSION['error_msg'] = 'No puede enviar una solicitud vacía.';
            header('Location: ' . $this->baseUrl() . '/pedido/actual');
            exit;
        }

        $res = $this->carritosModel->sendCarrito((int)$borrador['id'], $notas, $tipo_entrega, $direccion_envio);

        if ($res) {
            $_SESSION['success_msg'] = '¡Solicitud enviada correctamente! Por favor, espere a que sea revisada.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $borrador['id']);
            exit;
        }

        $_SESSION['error_msg'] = 'Ocurrió un error al procesar la compra.';
        header('Location: ' . $this->baseUrl() . '/pedido/actual');
        exit;
    }


    public function exito(): void
    {
        $this->view('carrito/exito', [
            'title' => '¡Compra Exitosa!'
        ]);
    }

    public function historial(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $carritos = $this->carritosModel->getHistoryByUserId($userId);

        $this->view('carrito/historial', [
            'title' => 'Mis Carritos',
            'carritos' => $carritos
        ]);
    }

    public function detalle(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $carritoId = (int)$id;

        if ($userRol === 1) {
            $carrito = $this->carritosModel->getByIdAdmin($carritoId);
        } else {
            $carrito = $this->carritosModel->getById($carritoId, $userId);
        }

        if (!$carrito) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $detalles = $this->carritosModel->getDetalles($carritoId);

        // Si no está facturado todavía, usamos la tasa BCV en vivo
        if ($carrito['estado_id'] < 4) {
            $tasaData = \App\Core\TasaBCV::getTasa();
            $carrito['tasabcv'] = $tasaData['tasa'];
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCarritoId($carritoId);

        $this->view('carrito/detalle', [
            'title'      => 'Detalle de Pedido #' . $carritoId,
            'carrito' => $carrito,
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

    private function generatePdfContent(int $carritoId, int $userId, int $userRol): ?string
    {
        if ($userRol === 1) {
            $carrito = $this->carritosModel->getByIdAdmin($carritoId);
        } else {
            $carrito = $this->carritosModel->getById($carritoId, $userId);
        }

        if (!$carrito) {
            return null;
        }

        $detalles = $this->carritosModel->getDetalles($carritoId);

        ob_start();
        require dirname(__DIR__) . '/Views/carrito/pdf_template.php';
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
        $carritoId = (int)$id;

        $pdfContent = $this->generatePdfContent($carritoId, $userId, $userRol);

        if (!$pdfContent) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Carrito_' . $carritoId . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo $pdfContent;
        exit;
    }

    private function generateFacturaPdfContent(int $carritoId, int $userId, int $userRol): ?string
    {
        if ($userRol === 1) {
            $carrito = $this->carritosModel->getByIdAdmin($carritoId);
        } else {
            $carrito = $this->carritosModel->getById($carritoId, $userId);
        }

        if (!$carrito) {
            return null;
        }

        $facturasModel = new \App\Models\FacturasModel();
        $factura = $facturasModel->getByCarritoId($carritoId);

        if (!$factura) {
            return null; // No invoice generated yet
        }

        $detalles = $this->carritosModel->getDetalles($carritoId);

        ob_start();
        require dirname(__DIR__) . '/Views/carrito/factura_pdf.php';
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
        $carritoId = (int)$id;

        $pdfContent = $this->generateFacturaPdfContent($carritoId, $userId, $userRol);

        if (!$pdfContent) {
            $_SESSION['error_msg'] = 'Factura no encontrada o no generada aún.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="factura_' . $carritoId . '.pdf"');
        echo $pdfContent;
        exit;
    }


    public function enviarCorreo(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $userRol = (int)($_SESSION['rol_id'] ?? 2);
        $carritoId = (int)$id;

        // Sólo admins
        if ($userRol !== 1) {
            $_SESSION['error_msg'] = 'No tiene permisos para enviar correos.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $carrito = $this->carritosModel->getByIdAdmin($carritoId);
        if (!$carrito) {
            $_SESSION['error_msg'] = 'Pedido/Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $pdfContent = $this->generatePdfContent($carritoId, $userId, $userRol);

        try {
            $mail = \App\Core\MailerService::make();

            $mail->addAddress($carrito['cliente_email'], $carrito['cliente_nombre']);

            $mail->isHTML(true);
            $mail->Subject = 'Cotización InstalFuego #' . str_pad((string)$carrito['id'], 4, '0', STR_PAD_LEFT);
            $mail->Body    = '<p>Estimado(a) ' . htmlspecialchars($carrito['cliente_nombre']) . ',</p>'
                           . '<p>Adjunto a este correo encontrará la cotización solicitada.</p>'
                           . '<p>Saludos cordiales,<br>El equipo de InstalFuego C.A.</p>';

            $mail->addStringAttachment($pdfContent, 'Carrito_' . $carritoId . '.pdf');

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
        $carritoId = (int)$id;

        $carrito = $this->carritosModel->getById($carritoId, $userId);
        
        if (!$carrito || $carrito['usuario_id'] != $userId) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        // Permitir pago si estado es listo_para_pago (3) o facturado (4)
        if (!in_array($carrito['estado_id'], [3, 4])) {
            $_SESSION['error_msg'] = 'Este presupuesto no está disponible para pago.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $carritoId);
            exit;
        }

        // Si no está facturado todavía, usamos la tasa BCV en vivo
        if ($carrito['estado_id'] < 4) {
            $tasaData = \App\Core\TasaBCV::getTasa();
            $carrito['tasabcv'] = $tasaData['tasa'];
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCarritoId($carritoId);

        if (!$pedido) {
            // Crear pedido automáticamente al intentar pagar
            $pedidoId = $pedidosModel->createFromCarrito(
                $carritoId, 
                $userId, 
                (float)$carrito['total'], 
                (float)$carrito['subtotal'], 
                (float)$carrito['impuestos'], 
                (float)$carrito['descuento']
            );
            $pedido = $pedidosModel->getById($pedidoId);
        }

        $detalles = $this->carritosModel->getDetalles($carritoId);
        $metodos = $this->carritosModel->getMetodosPago();

        $this->view('carrito/pagar', [
            'carrito' => $carrito,
            'pedido'     => $pedido,
            'detalles'   => $detalles,
            'subtotal'   => $carrito['subtotal'],
            'descuento'  => $carrito['descuento'],
            'iva'        => $carrito['impuestos'],
            'totalFinal' => $carrito['total'],
            'metodos'    => $metodos
        ]);
    }

    public function procesarPago(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $carritoId = (int)$id;

        $carrito = $this->carritosModel->getById($carritoId, $userId);
        if (!$carrito || $carrito['usuario_id'] != $userId) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $pedidosModel = new \App\Models\PedidosModel();
        $pedido = $pedidosModel->getByCarritoId($carritoId);

        if (!$pedido) {
            $_SESSION['error_msg'] = 'El pedido no ha sido inicializado. Visite la página de pago primero.';
            header('Location: ' . $this->baseUrl() . '/pedido/pagar/' . $carritoId);
            exit;
        }

        $metodoPagoId = (int)$_POST['metodo_pago_id'];
        $isCash = in_array($metodoPagoId, [3, 4]); // 3 = Efectivo, 4 = Divisas

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

        if (!$isCash && !$comprobante_url) {
            $_SESSION['error_msg'] = 'Debe subir un comprobante válido (JPG, PNG o PDF).';
            header('Location: ' . $this->baseUrl() . '/pedido/pagar/' . $carritoId);
            exit;
        }

        $referencia = $isCash ? 'EFECTIVO_PRESENCIAL' : strip_tags(trim($_POST['referencia'] ?? ''));

        $montoEsperado = isset($carrito['tasabcv']) ? round($carrito['total'] * $carrito['tasabcv'], 2) : 0;

        $data = [
            'pedido_id'        => $pedido['id'],
            'metodo_pago_id'   => $metodoPagoId,
            'monto'            => $montoEsperado,
            'moneda'           => $_POST['moneda'] ?? 'VES',
            'referencia'       => $referencia,
            'banco_origen'     => strip_tags(trim($_POST['banco_origen'] ?? '')),
            'telefono_pagador' => strip_tags(trim($_POST['telefono_pagador'] ?? '')),
            'comprobante_url'  => $comprobante_url
        ];

        if ($this->pagosModel->createPago($data)) {
            $_SESSION['success_msg'] = '¡Pago reportado exitosamente! Será validado a la brevedad.';
        } else {
            $_SESSION['error_msg'] = 'Ocurrió un error al reportar el pago.';
        }

        header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $carritoId);
        exit;
    }

    public function aceptar(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $carritoId = (int)$id;

        $carrito = $this->carritosModel->getById($carritoId, $userId);
        if (!$carrito || $carrito['estado_id'] != 3) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado o no está en estado emitido.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $db = Database::getInstance();
        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE carritos SET estado_id = 4 WHERE id = :id AND estado_id = 3");
            $stmt->execute([':id' => $carritoId]);

            // Crear el pedido asociado
            $stmtCheck = $db->prepare('SELECT id FROM pedidos WHERE carrito_id = :id');
            $stmtCheck->execute([':id' => $carritoId]);
            if (!$stmtCheck->fetch()) {
                $sqlPed = "INSERT INTO pedidos (carrito_id, usuario_id, total, costo_envio, estado_pedido, direccion_envio, tipo_entrega) 
                           VALUES (:cot_id, :usr_id, :tot, :envio, 'pendiente_pago', :dir, :tipo)";
                $stmtPed = $db->prepare($sqlPed);
                $stmtPed->execute([
                    ':cot_id' => $carritoId,
                    ':usr_id' => $carrito['usuario_id'],
                    ':tot'    => $carrito['total'],
                    ':envio'  => $carrito['costo_envio'],
                    ':dir'    => $carrito['direccion_envio'],
                    ':tipo'   => $carrito['tipo_entrega']
                ]);
            }

            $db->commit();
            $_SESSION['success_msg'] = 'Presupuesto aceptado correctamente. Su pedido se ha generado.';
        } catch (\PDOException $e) {
            $db->rollBack();
            error_log("Error al aceptar presupuesto: " . $e->getMessage());
            $_SESSION['error_msg'] = 'Ocurrió un error al aceptar el presupuesto.';
        }

        header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $carritoId);
        exit;
    }

    public function rechazar(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $carritoId = (int)$id;

        $carrito = $this->carritosModel->getById($carritoId, $userId);
        if (!$carrito || $carrito['estado_id'] != 3) {
            $_SESSION['error_msg'] = 'Presupuesto no encontrado o no está en estado emitido.';
            header('Location: ' . $this->baseUrl() . '/mis-pedidos');
            exit;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE carritos SET estado_id = 5 WHERE id = :id AND estado_id = 3");
        $stmt->execute([':id' => $carritoId]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = 'Presupuesto rechazado correctamente.';
        } else {
            $_SESSION['error_msg'] = 'No se pudo rechazar el presupuesto.';
        }

        header('Location: ' . $this->baseUrl() . '/mis-pedidos/' . $carritoId);
        exit;
    }
}
