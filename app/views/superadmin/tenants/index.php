<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Clientes Impact (Tenants)</h2>
        <a href="<?= url('superadmin/tenants/novo') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Novo Cliente
        </a>
    </div>

    <?php if ($success = Session::flash('success')): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>URL</th>
                    <th>Segmento</th>
                    <th>Plano</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $t): ?>
                <tr>
                    <td><?= e($t['name']) ?></td>
                    <td><code><?= BASE_URL ?>/<?= e($t['slug']) ?>/</code></td>
                    <td><?= e(ucfirst($t['business_type'])) ?></td>
                    <td><span class="badge bg-info"><?= e(ucfirst($t['plan'])) ?></span></td>
                    <td>
                        <span class="badge bg-<?= $t['active'] ? 'success' : 'secondary' ?>">
                            <?= $t['active'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/<?= e($t['slug']) ?>/" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>