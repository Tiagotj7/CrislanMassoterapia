<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Depoimentos</h2>
            <a href="<?= url('admin/depoimentos/novo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Novo Depoimento
            </a>
        </div>

        <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <div class="row g-3">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="stars mb-2">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i class="fa-solid fa-star <?= $i < $t['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="fst-italic small">"<?= e($t['comment']) ?>"</p>
                    <strong><?= e($t['client_name']) ?></strong>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-<?= $t['active'] ? 'success' : 'secondary' ?> toggle-badge" data-id="<?= $t['id'] ?>" style="cursor:pointer;">
                            <?= $t['active'] ? 'Visível' : 'Oculto' ?>
                        </span>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $t['id'] ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($testimonials)): ?>
            <div class="col-12 text-center text-muted py-4">Nenhum depoimento cadastrado.</div>
            <?php endif; ?>
        </div>
    </main>
</div>

<form id="csrf-holder"><?= csrf_field() ?></form>

<script>
const csrfToken = document.querySelector('#csrf-holder input[name="csrf_token"]').value;

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Excluir este depoimento?')) return;
        const res = await fetch(`${window.BASE_URL}/admin/depoimentos/excluir`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${btn.dataset.id}&csrf_token=${csrfToken}`
        });
        const data = await res.json();
        if (data.success) btn.closest('.col-md-4').remove();
    });
});

document.querySelectorAll('.toggle-badge').forEach(badge => {
    badge.addEventListener('click', async () => {
        const res = await fetch(`${window.BASE_URL}/admin/depoimentos/status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${badge.dataset.id}&csrf_token=${csrfToken}`
        });
        const data = await res.json();
        if (data.success) location.reload();
    });
});
</script>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>