<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CotizacionesModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\Core\Config;

class CotizacionClienteController extends Router
{
    private CotizacionesModel $cotizacionesModel;

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

        $borrador = $this->cotizacionesModel->getBorradorByUserId($userId);
        
        if (!$borrador) {
            $_SESSION['error_msg'] = 'No hay solicitud actual.';
            header('Location: ' . $this->baseUrl() . '/cotizacion/actual');
            exit;
        }

        $detalles = $this->cotizacionesModel->getDetalles((int)$borrador['id']);
        if (empty($detalles)) {
            $_SESSION['error_msg'] = 'No puede enviar una solicitud vacía.';
            header('Location: ' . $this->baseUrl() . '/cotizacion/actual');
            exit;
        }

        $res = $this->cotizacionesModel->sendCotizacion((int)$borrador['id'], $notas);

        if ($res) {
            $_SESSION['success_msg'] = '¡Solicitud de cotización enviada correctamente! Nos comunicaremos pronto.';
            header('Location: ' . $this->baseUrl() . '/cotizacion/exito');
            exit;
        }

        $_SESSION['error_msg'] = 'Ocurrió un error al enviar la solicitud.';
        header('Location: ' . $this->baseUrl() . '/cotizacion/actual');
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
            $_SESSION['error_msg'] = 'Cotización no encontrada o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-cotizaciones');
            exit;
        }

        $detalles = $this->cotizacionesModel->getDetalles($cotizacionId);

        $this->view('cotizacion/detalle', [
            'title' => 'Detalle de Solicitud #' . $cotizacionId,
            'cotizacion' => $cotizacion,
            'detalles' => $detalles
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
            $_SESSION['error_msg'] = 'Cotización no encontrada o no tiene permisos.';
            header('Location: ' . $this->baseUrl() . '/mis-cotizaciones');
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Cotizacion_' . $cotizacionId . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        echo $pdfContent;
        exit;
    }

    public function confirmarCotizacion(string $id): void
    {
        $userId = (int)$_SESSION['user_id'];
        $cotizacionId = (int)$id;

        $res = $this->cotizacionesModel->confirmarCotizacionCliente($cotizacionId, $userId);

        if ($res) {
            $_SESSION['success_msg'] = '¡Cotización confirmada exitosamente! Nos pondremos en contacto para coordinar los siguientes pasos.';
        } else {
            $_SESSION['error_msg'] = 'No se pudo confirmar la cotización. Verifica que esté en estado "Enviada" y que te pertenezca.';
        }

        header('Location: ' . $this->baseUrl() . '/mis-cotizaciones/' . $cotizacionId);
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
            header('Location: ' . $this->baseUrl() . '/mis-cotizaciones');
            exit;
        }

        $cotizacion = $this->cotizacionesModel->getByIdAdmin($cotizacionId);
        if (!$cotizacion) {
            $_SESSION['error_msg'] = 'Cotización no encontrada.';
            header('Location: ' . $this->baseUrl() . '/mis-cotizaciones');
            exit;
        }

        $pdfContent = $this->generatePdfContent($cotizacionId, $userId, $userRol);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = Config::SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = Config::SMTP_USER;
            $mail->Password   = Config::SMTP_PASS;
            $mail->Port       = Config::SMTP_PORT;

            $mail->setFrom(Config::SMTP_USER, 'InstalFuego C.A.');
            $mail->addAddress($cotizacion['cliente_email'], $cotizacion['cliente_nombre']);

            $mail->isHTML(true);
            $mail->Subject = 'Cotización InstalFuego #' . str_pad((string)$cotizacion['id'], 4, '0', STR_PAD_LEFT);
            $mail->Body    = '<p>Estimado(a) ' . htmlspecialchars($cotizacion['cliente_nombre']) . ',</p>'
                           . '<p>Adjunto a este correo encontrará la cotización solicitada.</p>'
                           . '<p>Saludos cordiales,<br>El equipo de InstalFuego C.A.</p>';

            $mail->addStringAttachment($pdfContent, 'Cotizacion_' . $cotizacionId . '.pdf');

            $mail->send();
            $_SESSION['success_msg'] = 'Cotización enviada por correo exitosamente.';
        } catch (PHPMailerException $e) {
            $_SESSION['error_msg'] = 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo;
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }
}
