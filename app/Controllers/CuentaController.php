<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\UsersModel;

class CuentaController extends Router
{
    private UsersModel $usersModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Requiere estar logueado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $this->usersModel = new UsersModel();
    }

    public function index(): void
    {
        $user = $this->usersModel->findById((int)$_SESSION['user_id']);
        if (!$user) {
            $this->logoutAndRedirect();
        }

        $this->view('cuenta/index', [
            'title' => 'Resumen de mi cuenta',
            'user' => $user
        ]);
    }

    public function perfil(): void
    {
        $user = $this->usersModel->findById((int)$_SESSION['user_id']);
        if (!$user) {
            $this->logoutAndRedirect();
        }

        $this->view('cuenta/perfil', [
            'title' => 'Mi Perfil',
            'user' => $user
        ]);
    }

    public function updatePerfil(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $nombre = strip_tags(trim($_POST['nombre'] ?? ''));
        $apellido = strip_tags(trim($_POST['apellido'] ?? ''));
        $cedula = strtoupper(strip_tags(trim($_POST['cedula'] ?? '')));
        $empresa = strip_tags(trim($_POST['empresa'] ?? ''));
        $telefono = strip_tags(trim($_POST['telefono'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (empty($nombre) || empty($apellido) || empty($cedula) || empty($telefono) || empty($email)) {
            $this->renderJsonError('Todos los campos obligatorios deben estar llenos.', $isAjax);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderJsonError('El formato del correo no es válido.', $isAjax);
            return;
        }

        $patronCedulaRif = '/^([VE]-\d{6,8}|[JVEGPC]-\d{8}-\d)$/';
        if (!preg_match($patronCedulaRif, $cedula)) {
            $this->renderJsonError('El formato de Cédula/RIF es inválido.', $isAjax);
            return;
        }

        // Validar si email o cedula ya existen en otro usuario
        $userByEmail = $this->usersModel->findByEmail($email);
        if ($userByEmail && (int)$userByEmail['id'] !== $userId) {
            $this->renderJsonError('El correo electrónico ya está en uso por otra cuenta.', $isAjax);
            return;
        }

        $userByCedula = $this->usersModel->findByCedula($cedula);
        if ($userByCedula && (int)$userByCedula['id'] !== $userId) {
            $this->renderJsonError('La cédula/RIF ya está registrada en otra cuenta.', $isAjax);
            return;
        }

        $updated = $this->usersModel->updateProfile($userId, $nombre, $apellido, $cedula, $empresa, $telefono, $email);

        if ($updated) {
            $_SESSION['user_name'] = $nombre; // actualizamos en sesión
            
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Perfil actualizado exitosamente.']);
                exit;
            }
            
            $_SESSION['success_msg'] = 'Perfil actualizado exitosamente.';
            header('Location: ' . $this->baseUrl() . '/cuenta/perfil');
            exit;
        }

        $this->renderJsonError('No se pudo actualizar el perfil.', $isAjax);
    }

    public function seguridad(): void
    {
        $user = $this->usersModel->findById((int)$_SESSION['user_id']);
        if (!$user) {
            $this->logoutAndRedirect();
        }

        $this->view('cuenta/seguridad', [
            'title' => 'Seguridad',
            'user' => $user
        ]);
    }

    public function updateSeguridad(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $actual = $_POST['password_actual'] ?? '';
        $nueva = $_POST['password_nueva'] ?? '';
        $confirmacion = $_POST['password_confirmacion'] ?? '';

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (empty($actual) || empty($nueva) || empty($confirmacion)) {
            $this->renderJsonError('Todos los campos son obligatorios.', $isAjax);
            return;
        }

        if ($nueva !== $confirmacion) {
            $this->renderJsonError('La nueva contraseña y la confirmación no coinciden.', $isAjax);
            return;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $nueva)) {
            $this->renderJsonError('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y caracteres especiales.', $isAjax);
            return;
        }

        $user = $this->usersModel->findById($userId);
        if (!$user || !password_verify($actual, $user['contrasena'])) {
            $this->renderJsonError('La contraseña actual es incorrecta.', $isAjax);
            return;
        }

        $hashedPassword = password_hash($nueva, PASSWORD_DEFAULT);
        $updated = $this->usersModel->updatePassword($userId, $hashedPassword);

        if ($updated) {
            $sessionToken = bin2hex(random_bytes(32));
            $this->usersModel->updateSessionToken($userId, $sessionToken);
            $_SESSION['session_token'] = $sessionToken;

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Contraseña actualizada exitosamente.']);
                exit;
            }
            $_SESSION['success_msg'] = 'Contraseña actualizada exitosamente.';
            header('Location: ' . $this->baseUrl() . '/cuenta/seguridad');
            exit;
        }

        $this->renderJsonError('No se pudo actualizar la contraseña.', $isAjax);
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

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }

    private function logoutAndRedirect(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $this->baseUrl() . '/login');
        exit;
    }
}
