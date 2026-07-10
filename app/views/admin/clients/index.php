<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Clientes</h2>
            <a href="<?= url('admin/clientes/novo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Novo Cliente
            </a>
        </div>

        <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="GET" class="mb-4 d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou telefone..." value="<?= e($search) ?>">
            <button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
        </form>

        <div class="card border-0 shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Atendimentos</th>
                            <th>Cadastrado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= e($client['name']) ?></td>
                            <td><?= e($client['phone']) ?></td>
                            <td><span class="badge bg-primary"><?= $client['total_appointments'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($client['created_at'])) ?></td>
                            <td class="d-flex gap-1">
                                <a href="<?= url('admin/clientes/' . $client['id']) ?>" class="btn btn-sm btn-outline-info" title="Ver histórico">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="<?= url('admin/clientes/' . $client['id'] . '/editar') ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger btn-delete-client" data-id="<?= $client['id'] ?>" title="Excluir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($clients)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Nenhum cliente encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </main>
</div>

<form id="csrf-holder">
    <?= csrf_field() ?>
</form>

<script>
document.querySelectorAll('.btn-delete-client').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Deseja realmente excluir este cliente? Isso não afeta o histórico de agendamentos.')) return;

        const csrfToken = document.querySelector('#csrf-holder input[name="csrf_token"]').value;
        const res = await fetch(`${window.BASE_URL}/admin/clientes/excluir`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${btn.dataset.id}&csrf_token=${csrfToken}`
        });
        const data = await res.json();
        if (data.success) {
            btn.closest('tr').remove();
        }
    });
});
</script>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>