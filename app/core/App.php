<?php
namespace App\Core;

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $router = $this->router; // disponível dentro de routes/web.php
        require ROOT_PATH . '/routes/web.php';
    }

    public function run(): void
    {
        $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }
}