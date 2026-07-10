<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex align-items-center justify-content-center vh-100 login-bg">
    <div class="card shadow-lg border-0 p-4" style="max-width: 420px; width: 100%;">
        <h4 class="fw-bold mb-3 text-center">Definir Nova Senha</h4>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/senha/atualizar') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Nova senha</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary w-100">Salvar Nova Senha</button>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>