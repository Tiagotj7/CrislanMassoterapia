<?php
namespace App\Controllers\SuperAdmin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\SuperAdminMiddleware;
use App\Models\TenantModel;

class TenantController extends Controller
{
    public function __construct()
    {
        SuperAdminMiddleware::handle();
    }

    public function index(): void
    {
        $model = new TenantModel();
        $this->view('superadmin/tenants/index', [
            'title'   => 'Clientes (Tenants) | Impact',
            'tenants' => $model->getAll(),
        ]);
    }

    public function create(): void
    {
        $businessTypes = require ROOT_PATH . '/config/business_types.php';
        $this->view('superadmin/tenants/form', [
            'title' => 'Novo Cliente',
            'types' => array_keys($businessTypes),
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('superadmin/tenants');
        }

        $name = sanitize($_POST['name'] ?? '');
        $slug = $this->slugify($_POST['slug'] ?? $name);
        $businessType = $_POST['business_type'] ?? 'outro';
        $plan = $_POST['plan'] ?? 'trial';

        $model = new TenantModel();

        if ($model->slugExists($slug)) {
            Session::flash('error', 'Já existe um cliente com essa URL. Escolha outro identificador.');
            $this->redirect('superadmin/tenants/novo');
        }

        $tenantId = $model->create([
            'slug'          => $slug,
            'name'          => $name,
            'business_type' => $businessType,
            'plan'          => $plan,
        ]);

        // Cria configurações padrão baseadas no segmento
        $model->seedDefaultSettings($tenantId, $businessType);

        // Cria o primeiro usuário admin deste tenant
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPassword = $_POST['admin_password'] ?? '';

        if ($adminEmail && $adminPassword) {
            $stmt = $this->db()->prepare(
                "INSERT INTO users (tenant_id, name, email, password) VALUES (:tid, :name, :email, :password)"
            );
            $stmt->execute([
                'tid'      => $tenantId,
                'name'     => $name,
                'email'    => $adminEmail,
                'password' => password_hash($adminPassword, PASSWORD_DEFAULT),
            ]);
        }

        Session::flash('success', "Cliente '{$name}' criado com sucesso! URL: /{$slug}/");
        $this->redirect('superadmin/tenants');
    }

    public function toggleActive(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new TenantModel())->toggleActive($id);

        $this->json(['success' => true, 'message' => 'Status atualizado.']);
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[áàãâä]/u', 'a', $text);
        $text = preg_replace('/[éèêë]/u', 'e', $text);
        $text = preg_replace('/[íìîï]/u', 'i', $text);
        $text = preg_replace('/[óòõôö]/u', 'o', $text);
        $text = preg_replace('/[úùûü]/u', 'u', $text);
        $text = preg_replace('/[ç]/u', 'c', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    private function db()
    {
        return \App\Core\Database::getInstance();
    }
}