<?php
namespace App\Middleware;

use App\Core\Session;
use App\Core\Tenant;

class AuthMiddleware
{
    public static function handle(): void
    {
        Session::start();

        $isLoggedIn = Session::has('admin_id');
        $sessionTenantId = Session::get('tenant_id');
        $currentTenantId = Tenant::id();

        // Impede que um admin logado no tenant A acesse o painel do tenant B
        if (!$isLoggedIn || $sessionTenantId !== $currentTenantId) {
            Session::remove('admin_id');
            Session::remove('admin_name');
            header('Location: ' . tenant_url('admin/login'));
            exit;
        }
    }
}