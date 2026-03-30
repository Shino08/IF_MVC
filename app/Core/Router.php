<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, array|callable $callback): void
    {
        $uri = trim($uri, '/');
        self::$routes['GET'][$uri] = $callback;
    }

    public static function post(string $uri, array|callable $callback): void
    {
        $uri = trim($uri, '/');
        self::$routes['POST'][$uri] = $callback;
    }

    public static function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $rootDir = dirname($scriptDir);

        if ($scriptDir !== '/' && $scriptDir !== '\\' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        } elseif ($rootDir !== '/' && $rootDir !== '\\' && strpos($uri, $rootDir) === 0) {
            $uri = substr($uri, strlen($rootDir));
        }

        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = trim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset(self::$routes[$method])) {
            http_response_code(404);
            echo '404 - Método no permitido';
            return;
        }

        foreach (self::$routes[$method] as $route => $callback) {
            $route = trim($route, '/');

            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                    return;
                }

                if (is_array($callback)) {
                    [$controllerName, $action] = $callback;

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $action)) {
                            call_user_func_array([$controller, $action], $matches);
                            return;
                        } else {
                            die("Método '$action' no encontrado en '$controllerName'");
                        }
                    } else {
                        die("Controlador '$controllerName' no encontrado");
                    }
                }
            }
        }

        http_response_code(404);
        echo '404 - Página no encontrada';
    }

    public function view(string $view, array $data = []): void
    {
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

    public function json(mixed $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
