<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Renderiza una vista ubicada en app/Views/
     *
     * @param string $view  Ruta relativa a app/Views/ sin extensión (ej: 'home', 'auth/login')
     * @param array  $data  Variables que estarán disponibles dentro de la vista
     */
    public function view(string $view, array $data = []): void
    {
        // Calcular base_url dinámicamente
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseUrl    = rtrim(str_replace('/index.php', '', $scriptName), '/');
        $data['base_url'] = $baseUrl;

        extract($data);

        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Error: La vista '$view' no existe en 'app/Views/'.");
        }
    }

    /**
     * Devuelve una respuesta JSON y termina la ejecución.
     *
     * @param mixed $data
     * @param int   $status Código HTTP
     */
    public function json(mixed $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
