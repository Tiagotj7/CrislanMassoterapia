<?php
namespace App\Middleware;

use App\Core\Tenant;
use App\Models\TenantModel;

class TenantMiddleware
{
    /**
     * Identifica o tenant pelo primeiro segmento da URL.
     * Retorna o restante do path (sem o slug) para o roteador continuar.
     */
    public static function resolve(string $path): string
    {
        $segments = explode('/', trim($path, '/'));
        $slug = $segments[0] ?? '';

        // Rotas reservadas que NÃO usam tenant (painel da Impact)
        $reserved = ['superadmin', 'assets', 'uploads', ''];
        if (in_array($slug, $reserved, true)) {
            return $path;
        }

        $tenantModel = new TenantModel();
        $tenant = $tenantModel->findBySlug($slug);

        if (!$tenant) {
            http_response_code(404);
            require ROOT_PATH . '/app/views/errors/tenant-not-found.php';
            exit;
        }

        Tenant::set($tenant);

        // Remove o slug do path e devolve o restante para o Router
        array_shift($segments);
        return implode('/', $segments);
    }
}