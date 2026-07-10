<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $model = new Testimonial();
        $this->view('admin/testimonials/index', [
            'title'        => 'Depoimentos | Painel Administrativo',
            'testimonials' => $model->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/testimonials/form', [
            'title'       => 'Novo Depoimento',
            'testimonial' => null,
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/depoimentos');
        }

        $name    = sanitize($_POST['client_name'] ?? '');
        $comment = sanitize($_POST['comment'] ?? '');
        $rating  = (int) ($_POST['rating'] ?? 5);
        $active  = isset($_POST['active']) ? 1 : 0;

        if (mb_strlen($name) < 3 || mb_strlen($comment) < 5) {
            Session::flash('error', 'Preencha nome e depoimento corretamente.');
            $this->redirect('admin/depoimentos/novo');
        }

        $rating = max(1, min(5, $rating));

        $model = new Testimonial();
        $model->create(compact('name', 'comment', 'rating', 'active') + ['client_name' => $name]);

        Session::flash('success', 'Depoimento cadastrado com sucesso.');
        $this->redirect('admin/depoimentos');
    }

    public function destroy(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new Testimonial())->delete($id);

        $this->json(['success' => true, 'message' => 'Depoimento excluído.']);
    }

    public function toggleStatus(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        (new Testimonial())->toggleStatus($id);

        $this->json(['success' => true, 'message' => 'Status atualizado.']);
    }
}