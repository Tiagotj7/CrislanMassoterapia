<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4">Novo Depoimento</h2>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
            <form method="POST" action="<?= url('admin/depoimentos/salvar') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nome do cliente *</label>
                    <input type="text" name="client_name" class="form-control" required minlength="3">
                </div>

                <div class="mb-3">
                    <label class="form-label">Depoimento *</label>
                    <textarea name="comment" class="form-control" rows="4" required minlength="5"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Avaliação (estrelas)</label>
                    <select name="rating" class="form-select">
                        <option value="5" selected>5 estrelas</option>
                        <option value="4">4 estrelas</option>
                        <option value="3">3 estrelas</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="active" class="form-check-input" id="active" checked>
                    <label class="form-check-label" for="active">Exibir na página inicial</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= url('admin/depoimentos') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>