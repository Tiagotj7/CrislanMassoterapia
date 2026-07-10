<?php
namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();
        if (!Session::has('admin_id')) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }
}