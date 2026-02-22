<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class HomeController extends Controller
{
    public function index(): void
    {
        // session_destroy();
        $isLoggedIn = isset($_SESSION['user_id']);
        $userName   = $_SESSION['user_name'] ?? 'Visitante';

        $data = [
            'title'     => 'Bienvenido a FrameworkMVC',
            'message'   => $isLoggedIn
                ? "¡Hola, $userName! Has iniciado sesión correctamente."
                : 'Este es un ejemplo de plantilla usando MVC. Inicia sesión para probar.',
            'logged_in' => $isLoggedIn,
        ];

        $this->view('home', $data);
    }
}
