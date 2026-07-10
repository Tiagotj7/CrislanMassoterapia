<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Galeria</h2>
            <a href="<?= url('admin/galeria/novo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Nova Imagem
            </a>
        </div>

        <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="row g-3">
            <?php foreach ($items as $item): ?>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="<?= url('uploads/' . $item['image']) ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                    <div class="card-body p-2 text-center">
                        <small class="d-block text-muted mb-2"><?= e($item['title'] ?? 'Sem título') ?></small>
                        <button class="btn btn-sm btn-outline-danger w-100 btn-delete" data-id="<?= $item['id'] ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($items)): ?>
            <div class="col-12 text-center text-muted py-4">Nenhuma imagem na galeria.</div>
            <?php endif; ?>
        </div>
    </main>
</div>

<form id="csrf-holder"><?= csrf_field() ?></form>

<script>
const csrfToken = document.querySelector('#csrf-holder input[name="csrf_token"]').value;
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Excluir esta imagem?')) return;
        const res = await fetch(`${window.BASE_URL}/admin/galeria/excluir`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${btn.dataset.id}&csrf_token=${csrfToken}`
        });
        const data = await res.json();
        if (data.success) btn.closest('.col-md-3').remove();
    });
});
</script>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>