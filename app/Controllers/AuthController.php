<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\UsersModel;
use App\Core\Config;

class AuthController extends Router
{
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/');
            exit;
        }

        $this->view('auth/login', ['title' => 'Iniciar Sesión']);
    }

    public function processLogin(): void
    {
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->renderLoginError('Todos los campos son obligatorios.', $email);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderLoginError('El formato del correo no es válido.', $email);
            return;
        }

        $userModel = new UsersModel();
        $user = $userModel->authenticate($email, $password);

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if ($user) {
            session_regenerate_id(true); 
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['user_name']     = $user['nombre'];
            $_SESSION['rol_id']        = $user['rol_id']; 
            $_SESSION['session_token'] = $user['session_token'];

            $redirectUrl = ($user['rol_id'] == 1) ? $this->baseUrl() . '/dashboard' : $this->baseUrl() . '/';

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Acceso concedido. Entrando...',
                    'redirect' => $redirectUrl 
                ]);
                exit;
            }

            header('Location: ' . $redirectUrl);
            exit;
        }

        $this->renderLoginError('Credenciales incorrectas.', $email);
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . $this->baseUrl() . '/');
        exit;
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Registro']);
    }

public function register(): void
    {
        $nombre           = strip_tags(trim($_POST['nombre'] ?? ''));
        $apellido         = strip_tags(trim($_POST['apellido'] ?? ''));
        $cedula           = strtoupper(strip_tags(trim($_POST['cedula'] ?? '')));
        $telefono         = strip_tags(trim($_POST['telefono'] ?? ''));
        $empresa          = strip_tags(trim($_POST['empresa'] ?? '')); 
        $email            = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password         = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $formData = compact('nombre', 'apellido', 'cedula', 'telefono', 'empresa', 'email');

        if (empty($nombre) || empty($apellido) || empty($cedula) || empty($telefono) || empty($email) || empty($password)) {
            $this->renderRegisterError('Por favor, completa todos los campos obligatorios.', $formData);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->renderRegisterError('El formato del correo no es válido.', $formData);
            return;
        }

        $patronCedulaRif = '/^([VE]-\d{6,8}|[JVEGPC]-\d{8}-\d)$/';
        if (!preg_match($patronCedulaRif, $cedula)) {
            $this->renderRegisterError('El formato de Cédula/RIF es inválido. Usa formatos como V-12345678 o J-12345678-9.', $formData);
            return;
        }

        if (strlen($password) < 8) {
            $this->renderRegisterError('La contraseña debe tener al menos 8 caracteres.', $formData);
            return;
        }

        if ($password !== $password_confirm) {
            $this->renderRegisterError('Las contraseñas no coinciden.', $formData);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel = new UsersModel();
        
        $user = $userModel->register($nombre, $apellido, $cedula, $empresa, $telefono, $email, $hashedPassword);

        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if ($user) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Cuenta creada con éxito. Redirigiendo...']);
                exit;
            }
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $this->renderRegisterError('El correo electrónico o la cédula ya están registrados, o hubo un error.', $formData);
    }

    private function renderLoginError(string $error, string $email): void
    {
        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $error]);
            exit;
        }

        $this->view('auth/login', [
            'title' => 'Iniciar Sesión',
            'error' => $error,
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        ]);
    }

    private function renderRegisterError(string $error, array $data): void
    {
        $isAjax = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $error]);
            exit;
        }

        $this->view('auth/register', array_merge(['title' => 'Registro', 'error' => $error], $data));
    }

    public function showOlvidePassword(): void
    {
        $this->view('auth/olvide-password', ['title' => 'Recuperar Contraseña']);
    }

    public function processOlvidePassword(): void
    {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/olvide-password', ['title' => 'Recuperar Contraseña', 'error' => 'Ingresa un correo electrónico válido.']);
            return;
        }

        $userModel = new UsersModel();
        $user = $userModel->findByEmail($email);

        if ($user) {
            // Rate limiting check
            if (!$userModel->canRequestPasswordReset((int)$user['id'])) {
                $this->view('auth/olvide-password', [
                    'title' => 'Recuperar Contraseña',
                    'error' => 'Has solicitado demasiados cambios de contraseña recientemente. Por favor, espera 15 minutos.'
                ]);
                return;
            }

            $token = bin2hex(random_bytes(32));
            $userModel->createPasswordResetToken((int)$user['id'], $token);

            $resetLink = $this->absoluteUrl() . '/reset-password?token=' . $token;
            
            // Send email using MailerService
            try {
                $mail = \App\Core\MailerService::make();
                
                $mail->addAddress($email, htmlspecialchars((string)$user['nombre']));
                
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de Contraseña - InstalFuego';
                $mail->Body    = "
                    <h2>Recuperación de Contraseña</h2>
                    <p>Hola {$user['nombre']},</p>
                    <p>Recibimos una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:</p>
                    <p><a href='{$resetLink}' style='background-color:#cc0000;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;'>Restablecer mi contraseña</a></p>
                    <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                ";
                $mail->AltBody = "Hola {$user['nombre']},\nPara restablecer tu contraseña, copia y pega el siguiente enlace en tu navegador:\n{$resetLink}";

                $mail->send();
            } catch (\Exception $e) {
                error_log("Error al enviar correo de recuperación: {$mail->ErrorInfo}");
                $_SESSION['resetlinkdemo'] = $resetLink;
            }
        }

        $this->view('auth/olvide-password', [
            'title' => 'Recuperar Contraseña', 
            'success' => 'Si tu correo está registrado, te hemos enviado las instrucciones para restablecer tu contraseña.'
        ]);
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $userModel = new UsersModel();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
            $this->view('auth/olvide-password', [
                'title' => 'Recuperar Contraseña',
                'error' => 'El enlace para restablecer la contraseña es inválido o ha expirado. Por favor, solicita uno nuevo.'
            ]);
            return;
        }

        $this->view('auth/reset-password', ['title' => 'Restablecer Contraseña', 'token' => $token]);
    }

    public function processResetPassword(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($password) || $password !== $password_confirm) {
            $this->view('auth/reset-password', ['title' => 'Restablecer Contraseña', 'token' => $token, 'error' => 'Las contraseñas no coinciden.']);
            return;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $this->view('auth/reset-password', ['title' => 'Restablecer Contraseña', 'token' => $token, 'error' => 'La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y caracteres especiales.']);
            return;
        }

        $userModel = new UsersModel();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
            $this->view('auth/reset-password', ['title' => 'Restablecer Contraseña', 'token' => $token, 'error' => 'Enlace inválido o expirado.']);
            return;
        }

        $userModel->resetPassword((int)$user['id'], password_hash($password, PASSWORD_DEFAULT), $token);

        $this->view('auth/login', ['title' => 'Iniciar Sesión', 'success' => 'Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.']);
    }

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }

    private function absoluteUrl(): string
    {
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        // Si tienes una variable de entorno APP_URL, mejor usarla
        if (getenv('APP_URL')) {
            return rtrim(getenv('APP_URL'), '/') . $this->baseUrl();
        }

        return $scheme . '://' . $host . $this->baseUrl();
    }
}