<?php
namespace App\Core;

use App\Middleware\TenantMiddleware;

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $uri, string $action): void { $this->add('GET', $uri, $action); }
    public function post(string $uri, string $action): void { $this->add('POST', $uri, $action); }

    private function add(string $method, string $uri, string $action): void
    {
        $uri = trim($uri, '/');
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $uri);
        $this->routes[$method][] = ['pattern' => '#^' . $pattern . '$#', 'action' => $action];
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $path = trim((string) parse_url($requestUri, PHP_URL_PATH), '/');

        $basePath = trim((string) parse_url(BASE_URL, PHP_URL_PATH), '/');
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = trim(substr($path, strlen($basePath)), '/');
        }

        // ---- Resolve o tenant ANTES de rotear (exceto rotas de superadmin) ----
        $firstSegment = explode('/', $path)[0] ?? '';
        if ($firstSegment !== 'superadmin') {
            $path = TenantMiddleware::resolve($path);
        }

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $this->call($route['action'], $matches);
                return;
            }
        }

        $this->notFound();
    }

    private function call(string $action, array $params): void
    {
        [$controllerName, $methodName] = explode('@', $action);
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $methodName)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerClass();
        call_user_func_array([$controller, $methodName], $params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        require ROOT_PATH . '/app/views/errors/404.php';
    }
}