<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\User;

class PasswordRecoveryController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    /** Etapa 1: informar e-mail */
    public function showForgotForm(): void
    {
        $this->view('admin/auth/forgot', ['title' => 'Recuperar Senha']);
    }

    /** Etapa 2: buscar pergunta de segurança */
    public function findQuestion(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('admin/senha/esqueci');
        }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $userModel = new User();

        $question = $userModel->getSecurityQuestion($email);

        if (!$question) {
            // Mensagem genérica por segurança (não revela se o e-mail existe)
            Session::flash('error', 'Não foi possível localizar uma pergunta de segurança para este e-mail. Entre em contato com o suporte técnico.');
            $this->redirect('admin/senha/esqueci');
        }

        Session::set('recovery_email', $email);
        Session::set('recovery_question', $question);

        $this->redirect('admin/senha/pergunta');
    }

    /** Etapa 3: exibir pergunta e validar resposta */
    public function showQuestionForm(): void
    {
        $email = Session::get('recovery_email');
        $question = Session::get('recovery_question');

        if (!$email || !$question) {
            $this->redirect('admin/senha/esqueci');
        }

        $this->view('admin/auth/security-question', [
            'title'    => 'Pergunta de Segurança',
            'question' => $question,
        ]);
    }

    public function verifyAnswer(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('admin/senha/esqueci');
        }

        $email = Session::get('recovery_email');
        $answer = trim($_POST['answer'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        if (!$email) {
            $this->redirect('admin/senha/esqueci');
        }

        $userModel = new User();

        if ($userModel->countRecoveryAttempts($email) >= self::MAX_ATTEMPTS) {
            Session::flash('error', 'Muitas tentativas incorretas. Aguarde 15 minutos.');
            $this->redirect('admin/senha/esqueci');
        }

        $userId = $userModel->verifySecurityAnswer($email, $answer);

        if (!$userId) {
            $userModel->registerFailedRecovery($email, $ip);
            Session::flash('error', 'Resposta incorreta. Tente novamente.');
            $this->redirect('admin/senha/pergunta');
        }

        // Resposta correta: gera token temporário de sessão para permitir troca de senha
        $token = bin2hex(random_bytes(32));
        Session::set('recovery_verified_token', $token);
        Session::set('recovery_user_id', $userId);
        Session::remove('recovery_question');

        $this->redirect('admin/senha/redefinir');
    }

    /** Etapa 4: definir nova senha */
    public function showResetForm(): void
    {
        if (!Session::has('recovery_verified_token') || !Session::has('recovery_user_id')) {
            $this->redirect('admin/senha/esqueci');
        }

        $this->view('admin/auth/reset-password', ['title' => 'Nova Senha']);
    }

    public function resetPassword(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada.');
            $this->redirect('admin/senha/esqueci');
        }

        $userId = Session::get('recovery_user_id');
        if (!$userId) {
            $this->redirect('admin/senha/esqueci');
        }

        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? '';

        if (mb_strlen($password) < 8) {
            Session::flash('error', 'A senha deve ter no mínimo 8 caracteres.');
            $this->redirect('admin/senha/redefinir');
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'As senhas não coincidem.');
            $this->redirect('admin/senha/redefinir');
        }

        $userModel = new User();
        $userModel->updatePassword((int) $userId, $password);

        // Limpa toda a sessão de recuperação
        Session::remove('recovery_email');
        Session::remove('recovery_verified_token');
        Session::remove('recovery_user_id');

        Session::flash('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        $this->redirect('admin/login');
    }
}