<?php
/** @var array $types */
require ROOT_PATH . '/app/views/admin/layouts/header.php';
?>
<?php use App\Core\Session; ?>

<div class="p-4">
    <h2 class="fw-bold mb-4">Novo Cliente</h2>

    <?php if ($error = Session::flash('error')): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
        <form method="POST" action="<?= url('superadmin/tenants/salvar') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nome do negócio *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">URL personalizada (slug)</label>
                <div class="input-group">
                    <span class="input-group-text"><?= BASE_URL ?>/</span>
                    <input type="text" name="slug" class="form-control" placeholder="deixe em branco para gerar automaticamente">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Segmento *</label>
                <select name="business_type" class="form-select">
                    <?php foreach ($types as $type): ?>
                        <option value="<?= e($type) ?>"><?= e(ucfirst($type)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Plano</label>
                <select name="plan" class="form-select">
                    <option value="trial">Trial (14 dias)</option>
                    <option value="basic">Básico</option>
                    <option value="pro">Pro</option>
                </select>
            </div>

            <hr>
            <h6>Primeiro usuário administrador</h6>

            <div class="mb-3">
                <label class="form-label">E-mail do admin *</label>
                <input type="email" name="admin_email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha inicial *</label>
                <input type="text" name="admin_password" class="form-control" required minlength="8">
            </div>

            <button type="submit" class="btn btn-primary">Criar Cliente</button>
        </form>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>