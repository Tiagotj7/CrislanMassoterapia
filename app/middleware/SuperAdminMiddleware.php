<?php
namespace App\Middleware;

use App\Core\Session;

class SuperAdminMiddleware
{
    public static function handle(): void
    {
        Session::start();
        if (!Session::has('super_admin_id')) {
            header('Location: ' . BASE_URL . '/superadmin/login');
            exit;
        }
    }
}