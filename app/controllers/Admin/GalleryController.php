<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Helpers\ImageHelper;
use App\Middleware\AuthMiddleware;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $model = new Gallery();
        $this->view('admin/gallery/index', [
            'title' => 'Galeria | Painel Administrativo',
            'items' => $model->getAllActive(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/gallery/form', ['title' => 'Nova Imagem']);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/galeria');
        }

        if (empty($_FILES['image']['name'])) {
            Session::flash('error', 'Selecione uma imagem para enviar.');
            $this->redirect('admin/galeria/novo');
        }

        try {
            $filename = ImageHelper::upload($_FILES['image'], 'gallery', 1000);
        } catch (\RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('admin/galeria/novo');
        }

        $model = new Gallery();
        $model->create([
            'title'          => sanitize($_POST['title'] ?? ''),
            'image'          => $filename,
            'order_position' => (int) ($_POST['order_position'] ?? 0),
            'active'         => 1,
        ]);

        Session::flash('success', 'Imagem adicionada à galeria.');
        $this->redirect('admin/galeria');
    }

    public function destroy(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new Gallery())->delete($id); // já remove o arquivo físico (ver Model)

        $this->json(['success' => true, 'message' => 'Imagem excluída.']);
    }
}