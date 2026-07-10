<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    public function showLogin(): void
    {
        if (Session::has('admin_id')) {
            $this->redirect('admin/dashboard');
        }

        $this->view('admin/auth/login', ['title' => 'Login | Painel Administrativo']);
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/login');
        }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $userModel = new User();

        // Proteção contra força bruta
        if ($userModel->verifyLoginAttempts($email) >= self::MAX_ATTEMPTS) {
            Session::flash('error', 'Muitas tentativas. Aguarde 15 minutos e tente novamente.');
            $this->redirect('admin/login');
        }

        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $userModel->registerFailedAttempt($email, $ip);
            Session::flash('error', 'E-mail ou senha inválidos.');
            $this->redirect('admin/login');
        }

        // Login bem-sucedido
        session_regenerate_id(true); // Previne session fixation
        Session::set('admin_id', $user['id']);
        Session::set('admin_name', $user['name']);
        $userModel->registerLogin($user['id'], $ip);

        $this->redirect('admin/dashboard');
    }

    public function logout(): void
    {
        Session::remove('admin_id');
        Session::remove('admin_name');
        session_destroy();
        $this->redirect('admin/login');
    }
}