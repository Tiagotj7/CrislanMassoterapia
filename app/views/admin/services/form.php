<?php
/** @var array|null $service */
require ROOT_PATH . '/app/views/admin/layouts/header.php';
use App\Core\Session;
?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4"><?= $service ? 'Editar Serviço' : 'Novo Serviço' ?></h2>

        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4" style="max-width: 600px;">
            <form method="POST" enctype="multipart/form-data"
                  action="<?= $service ? url('admin/servicos/' . $service['id'] . '/atualizar') : url('admin/servicos/salvar') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nome do serviço *</label>
                    <input type="text" name="name" class="form-control" required minlength="3"
                           value="<?= e($service['name'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-control" rows="3"><?= e($service['description'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duração (minutos) *</label>
                        <input type="number" name="duration_minutes" class="form-control" required min="15" step="5"
                               value="<?= e($service['duration_minutes'] ?? '60') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preço (R$) *</label>
                        <input type="text" name="price" class="form-control" required
                               value="<?= isset($service['price']) ? number_format($service['price'], 2, ',', '.') : '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagem do serviço</label>
                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <?php if (!empty($service['image'])): ?>
                        <img src="<?= url('uploads/' . $service['image']) ?>" class="mt-2 rounded" style="max-width:150px;">
                    <?php endif; ?>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="active" class="form-check-input" id="active"
                           <?= (!isset($service['active']) || $service['active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="active">Serviço ativo (visível para clientes)</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= url('admin/servicos') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>