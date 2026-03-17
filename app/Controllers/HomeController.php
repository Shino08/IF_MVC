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
        $sesionIniciada = isset($_SESSION['user_id']);
        $nombreUsuario   = $_SESSION['user_name'] ?? 'Visitante';

        $data = [
            'title'     => 'InstalFuego — Sistemas de Seguridad Contra Incendios',
            'message'   => $sesionIniciada
                ? "¡Hola, $nombreUsuario! Has iniciado sesión correctamente."
                : 'Bienvenido a InstalFuego, tu proveedor de sistemas de seguridad contra incendios.',
            'logged_in' => $sesionIniciada,
        ];

        $this->view('home', $data);
    }
}
