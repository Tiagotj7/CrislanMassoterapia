<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Models\Client;

class ClientController extends Controller
{
    private const PER_PAGE = 15;

    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');

        $clientModel = new Client();
        $clients = $clientModel->paginate($page, self::PER_PAGE, $search);
        $total = $clientModel->countAll($search);
        $totalPages = (int) ceil($total / self::PER_PAGE);

        $this->view('admin/clients/index', [
            'title'      => 'Clientes | Painel Administrativo',
            'clients'    => $clients,
            'search'     => $search,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/clients/form', [
            'title'  => 'Novo Cliente',
            'client' => null,
        ]);
    }

    public function store(): void
    {
        $this->validateAndSave();
    }

    public function edit(string $id): void
    {
        $clientModel = new Client();
        $client = $clientModel->find((int) $id);

        if (!$client) {
            $this->redirect('admin/clientes');
        }

        $this->view('admin/clients/form', [
            'title'  => 'Editar Cliente',
            'client' => $client,
        ]);
    }

    public function update(string $id): void
    {
        $this->validateAndSave((int) $id);
    }

    public function show(string $id): void
    {
        $clientModel = new Client();
        $client = $clientModel->find((int) $id);

        if (!$client) {
            $this->redirect('admin/clientes');
        }

        $history = $clientModel->getAppointmentHistory((int) $id);

        $this->view('admin/clients/show', [
            'title'   => 'Detalhes do Cliente',
            'client'  => $client,
            'history' => $history,
        ]);
    }

    public function destroy(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $clientModel = new Client();
        $clientModel->delete($id);

        $this->json(['success' => true, 'message' => 'Cliente excluído com sucesso.']);
    }

    private function validateAndSave(?int $id = null): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/clientes');
        }

        $name = sanitize($_POST['name'] ?? '');
        $phone = only_digits($_POST['phone'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $notes = sanitize($_POST['notes'] ?? '');

        $errors = [];
        if (mb_strlen($name) < 3) {
            $errors[] = 'Nome deve ter ao menos 3 caracteres.';
        }
        if (mb_strlen($phone) < 10) {
            $errors[] = 'Telefone inválido.';
        }

        $clientModel = new Client();
        if ($clientModel->existsPhone($phone, $id)) {
            $errors[] = 'Já existe um cliente com este telefone.';
        }

        if (!empty($errors)) {
            Session::flash('error', implode(' ', $errors));
            $this->redirect($id ? "admin/clientes/{$id}/editar" : 'admin/clientes/novo');
        }

        $data = compact('name', 'phone', 'email', 'notes');

        if ($id) {
            $clientModel->update($id, $data);
            Session::flash('success', 'Cliente atualizado com sucesso.');
        } else {
            $clientModel->create($data);
            Session::flash('success', 'Cliente cadastrado com sucesso.');
        }

        $this->redirect('admin/clientes');
    }
}