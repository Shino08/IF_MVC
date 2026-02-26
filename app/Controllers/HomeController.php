<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\UsersModel;

class HomeController extends Router
{
    public function index(): void
    {
        // session_destroy();
        $isLoggedIn = isset($_SESSION['user_id']);
        $userName   = $_SESSION['user_name'] ?? 'Visitante';

        $data = [
            'title'     => 'InstalFuego — Sistemas de Seguridad Contra Incendios',
            'message'   => $isLoggedIn
                ? "¡Hola, $userName! Has iniciado sesión correctamente."
                : 'Bienvenido a InstalFuego, tu proveedor de sistemas de seguridad contra incendios.',
            'logged_in' => $isLoggedIn,
        ];

        $this->view('home', $data);
    }
}
