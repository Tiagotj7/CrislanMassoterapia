<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php

use App\Core\Session;
use App\Core\Csrf; ?>

<div class="d-flex align-items-center justify-content-center vh-100 login-bg">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <img src="<?= asset('img/logo.png') ?>" alt="Crislan" style="max-width: 100px;">
            <h4 class="mt-3 fw-bold">Painel Administrativo</h4>
        </div>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/login') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
            <!-- Adicionar logo após o botão "Entrar" -->
            <div class="text-center mt-3">
                <a href="<?= url('admin/senha/esqueci') ?>" class="small text-muted">Esqueci minha senha</a>
            </div>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>