<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\Usuario;

class AuthController extends Router
{
    // Mostrar formulario de login
    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . $this->baseUrl() . '/');
            exit;
        }

        $this->view('auth/login', ['title' => 'Iniciar Sesión']);
    }

    // Procesar datos del login
    public function processLogin(): void
    {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new Usuario();
        $user      = $userModel->findByEmail($email);

        if ($user && $password === $user['password']) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: ' . $this->baseUrl() . '/');
            exit;
        }

        $this->view('auth/login', [
            'title' => 'Iniciar Sesión',
            'error' => 'Credenciales incorrectas',
            'email' => htmlspecialchars($email),
        ]);
    }

    public function logout(): void
    {
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
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';
        $name     = $_POST['name']     ?? '';
        $lastname = $_POST['lastname'] ?? '';

        $userModel = new Usuario();
        $user      = $userModel->register($email, $name, $lastname, $password);

        if ($user) {
            header('Location: ' . $this->baseUrl() . '/login');
            exit;
        }

        $this->view('auth/register', [
            'title'    => 'Registro',
            'error'    => 'El correo electrónico ya está registrado',
            'email'    => htmlspecialchars($email),
            'name'     => htmlspecialchars($name),
            'lastname' => htmlspecialchars($lastname),
        ]);
    }

    // ── Helper privado ──────────────────────────────────────────────────────
    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }
}
