<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex align-items-center justify-content-center vh-100 login-bg">
    <div class="card shadow-lg border-0 p-4" style="max-width: 420px; width: 100%;">
        <h4 class="fw-bold mb-2 text-center">Recuperar Senha</h4>
        <p class="text-muted text-center small mb-4">Informe o e-mail cadastrado para continuar</p>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/senha/buscar') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Continuar</button>
            <a href="<?= url('admin/login') ?>" class="btn btn-link w-100 mt-2">Voltar ao login</a>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>