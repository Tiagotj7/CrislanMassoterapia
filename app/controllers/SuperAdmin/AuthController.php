<?php
namespace App\Controllers\SuperAdmin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\SuperAdmin;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('superadmin/auth/login', ['title' => 'Login Impact']);
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('superadmin/login');
        }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $model = new SuperAdmin();
        $admin = $model->findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            Session::flash('error', 'Credenciais inválidas.');
            $this->redirect('superadmin/login');
        }

        session_regenerate_id(true);
        Session::set('super_admin_id', $admin['id']);
        Session::set('super_admin_name', $admin['name']);

        $this->redirect('superadmin/tenants');
    }

    public function logout(): void
    {
        Session::remove('super_admin_id');
        $this->redirect('superadmin/login');
    }
}