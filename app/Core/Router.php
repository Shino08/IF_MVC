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
        $uri = $_SERVER['REQUEST_URI'];

        // Detectar si estamos en una subcarpeta (ej: /FrameworkMVC/public/)
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptPath !== '/' && strpos($uri, $scriptPath) === 0) {
            $uri = substr($uri, strlen($scriptPath));
        }

        // Limpiar query strings (?foo=bar)
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // Normalizar: la raíz siempre es string vacío ''
        $uri = trim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset(self::$routes[$method])) {
            http_response_code(404);
            echo '404 - Método no permitido';
            return;
        }

        foreach (self::$routes[$method] as $route => $callback) {
            // Normalizar la ruta registrada igual que la URI
            $route = trim($route, '/');

            // Construir patrón regex:
            // Ruta raíz ('') → patrón #^$#  |  Otras rutas → #^ruta$#
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Quitar la coincidencia completa

                // Ejecutar Closure
                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                    return;
                }

                // Ejecutar [ControllerName::class, 'method']
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
}
