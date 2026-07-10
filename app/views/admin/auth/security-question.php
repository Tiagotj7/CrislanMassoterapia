<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex align-items-center justify-content-center vh-100 login-bg">
    <div class="card shadow-lg border-0 p-4" style="max-width: 420px; width: 100%;">
        <h4 class="fw-bold mb-3 text-center">Pergunta de Segurança</h4>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <p class="fw-semibold"><?= e($question) ?></p>

        <form method="POST" action="<?= url('admin/senha/verificar') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <input type="text" name="answer" class="form-control" required autofocus placeholder="Sua resposta">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verificar</button>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>