<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Helpers\ImageHelper;
use App\Middleware\AuthMiddleware;
use App\Models\Service;

class ServiceController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $serviceModel = new Service();
        $this->view('admin/services/index', [
            'title'    => 'Serviços | Painel Administrativo',
            'services' => $serviceModel->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/services/form', [
            'title'   => 'Novo Serviço',
            'service' => null,
        ]);
    }

    public function edit(string $id): void
    {
        $serviceModel = new Service();
        $service = $serviceModel->find((int) $id);

        if (!$service) {
            $this->redirect('admin/servicos');
        }

        $this->view('admin/services/form', [
            'title'   => 'Editar Serviço',
            'service' => $service,
        ]);
    }

    public function store(): void
    {
        $this->validateAndSave();
    }

    public function update(string $id): void
    {
        $this->validateAndSave((int) $id);
    }

    public function destroy(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $serviceModel = new Service();

        if ($serviceModel->hasAppointments($id)) {
            $this->json([
                'success' => false,
                'message' => 'Não é possível excluir: este serviço possui agendamentos vinculados. Desative-o em vez de excluir.'
            ], 422);
        }

        $service = $serviceModel->find($id);
        $serviceModel->delete($id);

        if ($service && $service['image']) {
            ImageHelper::delete($service['image']);
        }

        $this->json(['success' => true, 'message' => 'Serviço excluído com sucesso.']);
    }

    public function toggleStatus(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $serviceModel = new Service();
        $serviceModel->toggleStatus($id);

        $this->json(['success' => true, 'message' => 'Status atualizado.']);
    }

    private function validateAndSave(?int $id = null): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/servicos');
        }

        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $duration = (int) ($_POST['duration_minutes'] ?? 0);
        $price = (float) str_replace(',', '.', $_POST['price'] ?? '0');
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if (mb_strlen($name) < 3) {
            $errors[] = 'Nome do serviço inválido.';
        }
        if ($duration <= 0) {
            $errors[] = 'Duração deve ser maior que zero.';
        }
        if ($price < 0) {
            $errors[] = 'Preço inválido.';
        }

        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            $this->redirect($id ? "admin/servicos/{$id}/editar" : 'admin/servicos/novo');
        }

        $data = [
            'name'             => $name,
            'description'      => $description,
            'duration_minutes' => $duration,
            'price'            => $price,
            'active'           => $active,
        ];

        // Upload de imagem (opcional)
        if (!empty($_FILES['image']['name'])) {
            try {
                $serviceModel = new Service();

                // Remove imagem antiga se estiver editando
                if ($id) {
                    $existing = $serviceModel->find($id);
                    if ($existing && $existing['image']) {
                        ImageHelper::delete($existing['image']);
                    }
                }

                $data['image'] = ImageHelper::upload($_FILES['image'], 'services', 800);
            } catch (\RuntimeException $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect($id ? "admin/servicos/{$id}/editar" : 'admin/servicos/novo');
            }
        }

        $serviceModel = new Service();

        if ($id) {
            $serviceModel->update($id, $data);
            Session::flash('success', 'Serviço atualizado com sucesso.');
        } else {
            $serviceModel->create($data);
            Session::flash('success', 'Serviço cadastrado com sucesso.');
        }

        $this->redirect('admin/servicos');
    }
}