<?php
namespace App\Core;

abstract class Controller
{
    /** Renderiza uma view dentro do layout padrão */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = ROOT_PATH . "/app/views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View não encontrada: {$view}");
        }

        require $viewPath;
    }

    /** Retorna resposta JSON (usado nas chamadas AJAX) */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }
}