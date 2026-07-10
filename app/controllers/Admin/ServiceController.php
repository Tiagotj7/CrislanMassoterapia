<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Helpers\ImageHelper;
use App\Models\Service;

class ServiceController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $model = new Service();
        $this->view('admin/services/index', [
            'title'    => 'Serviços | Painel Administrativo',
            'services' => $model->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/services/form', [
            'title'   => 'Novo Serviço',
            'service' => null,
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/servicos');
        }

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $duration = (int) ($_POST['duration_minutes'] ?? 60);
        $priceRaw = str_replace(['.', 'R$', ','], ['', '', '.'], $_POST['price'] ?? '0');
        $price = (float) $priceRaw;
        $active = isset($_POST['active']) ? 1 : 0;

        $filename = null;
        if (!empty($_FILES['image']['name'])) {
            try {
                $filename = ImageHelper::upload($_FILES['image'], 'services', 800);
            } catch (\RuntimeException $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect('admin/servicos/novo');
            }
        }

        $model = new Service();
        $model->create([
            'name'            => $name,
            'description'     => $description,
            'duration_minutes'=> $duration,
            'price'           => $price,
            'image'           => $filename,
            'active'          => $active,
        ]);

        Session::flash('success', 'Serviço cadastrado com sucesso.');
        $this->redirect('admin/servicos');
    }

    public function edit(string $id): void
    {
        $model = new Service();
        $service = $model->find((int) $id);
        if (!$service) {
            $this->redirect('admin/servicos');
        }

        $this->view('admin/services/form', [
            'title'   => 'Editar Serviço',
            'service' => $service,
        ]);
    }

    public function update(string $id): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/servicos');
        }

        $model = new Service();
        $service = $model->find((int) $id);
        if (!$service) {
            $this->redirect('admin/servicos');
        }

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $duration = (int) ($_POST['duration_minutes'] ?? 60);
        $priceRaw = str_replace(['.', 'R$', ','], ['', '', '.'], $_POST['price'] ?? '0');
        $price = (float) $priceRaw;
        $active = isset($_POST['active']) ? 1 : 0;

        $filename = null;

        $data = [
            'name'             => $name,
            'description'      => $description,
            'duration_minutes' => $duration,
            'price'            => $price,
            'active'           => $active,
        ];

        if (!empty($_FILES['image']['name'])) {
            try {
                $filename = ImageHelper::upload($_FILES['image'], 'services', 800);
                // remove imagem antiga
                \App\Helpers\ImageHelper::delete($service['image'] ?? null);
                $data['image'] = $filename;
            } catch (\RuntimeException $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect("admin/servicos/{$id}/editar");
            }
        }

        $model->update((int) $id, $data);
        Session::flash('success', 'Serviço atualizado com sucesso.');
        $this->redirect('admin/servicos');
    }

    public function destroy(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $model = new Service();
        if ($model->hasAppointments($id)) {
            $this->json(['success' => false, 'message' => 'Este serviço possui agendamentos e não pode ser excluído.']);
        }

        $service = $model->find($id);
        if ($service) {
            \App\Helpers\ImageHelper::delete($service['image'] ?? null);
        }

        $model->delete($id);
        $this->json(['success' => true, 'message' => 'Serviço excluído com sucesso.']);
    }

    public function toggleStatus(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new Service())->toggleStatus($id);
        $this->json(['success' => true, 'message' => 'Status atualizado.']);
    }
}