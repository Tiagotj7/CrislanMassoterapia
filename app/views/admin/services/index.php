<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Serviços</h2>
            <a href="<?= url('admin/servicos/novo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Novo Serviço
            </a>
        </div>

        <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="row g-3">
            <?php foreach ($services as $service): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <?php if ($service['image']): ?>
                        <img src="<?= url('uploads/' . $service['image']) ?>" class="card-img-top" style="height:180px;object-fit:cover;" alt="<?= e($service['name']) ?>">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                            <i class="fa-solid fa-spa fs-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= e($service['name']) ?></h5>
                        <p class="text-muted small"><?= e($service['description']) ?></p>
                        <p class="mb-1"><strong><?= $service['duration_minutes'] ?> min</strong> · R$ <?= number_format($service['price'], 2, ',', '.') ?></p>
                        <span class="badge bg-<?= $service['active'] ? 'success' : 'secondary' ?>">
                            <?= $service['active'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex gap-2">
                        <a href="<?= url('admin/servicos/' . $service['id'] . '/editar') ?>" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <button class="btn btn-sm btn-outline-danger btn-delete-service" data-id="<?= $service['id'] ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<form id="csrf-holder">
    <?= csrf_field() ?>
</form>

<script>
document.querySelectorAll('.btn-delete-service').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Deseja realmente excluir este serviço?')) return;

        const csrfToken = document.querySelector('#csrf-holder input[name="csrf_token"]').value;
        const res = await fetch(`${window.BASE_URL}/admin/servicos/excluir`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${btn.dataset.id}&csrf_token=${csrfToken}`
        });
        const data = await res.json();
        if (data.success) {
            btn.closest('.col-md-4').remove();
        } else {
            alert(data.message);
        }
    });
});
</script>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>