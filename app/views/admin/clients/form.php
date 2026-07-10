<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4"><?= $client ? 'Editar Cliente' : 'Novo Cliente' ?></h2>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
            <form method="POST" action="<?= $client ? url('admin/clientes/' . $client['id'] . '/atualizar') : url('admin/clientes/salvar') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nome completo *</label>
                    <input type="text" name="name" class="form-control" required minlength="3"
                           value="<?= e($client['name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefone (WhatsApp) *</label>
                    <input type="tel" name="phone" class="form-control" required minlength="10"
                           value="<?= e($client['phone'] ?? '') ?>" placeholder="(00) 00000-0000">
                </div>

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= e($client['email'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="notes" class="form-control" rows="3"><?= e($client['notes'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= url('admin/clientes') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>