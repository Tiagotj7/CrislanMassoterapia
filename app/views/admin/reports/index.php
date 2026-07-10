<?php
/** @var string $startDate */
/** @var string $endDate */
/** @var array $summary */
/** @var array $topServices */
/** @var array $topClients */
require ROOT_PATH . '/app/views/admin/layouts/header.php';
?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Relatórios</h2>
            <a href="<?= url('admin/relatorios/exportar') ?>?start=<?= $startDate ?>&end=<?= $endDate ?>" class="btn btn-outline-primary">
                <i class="fa-solid fa-file-csv me-1"></i> Exportar CSV
            </a>
        </div>

        <form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
            <input type="date" name="start" value="<?= e($startDate) ?>" class="form-control" style="max-width:180px;">
            <input type="date" name="end" value="<?= e($endDate) ?>" class="form-control" style="max-width:180px;">
            <button class="btn btn-outline-secondary">Filtrar</button>
        </form>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <h4 class="fw-bold mb-0"><?= $summary['total_appointments'] ?? 0 ?></h4>
                    <small class="text-muted">Total de agendamentos</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <h4 class="fw-bold mb-0 text-success"><?= $summary['completed'] ?? 0 ?></h4>
                    <small class="text-muted">Concluídos</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <h4 class="fw-bold mb-0 text-danger"><?= $summary['cancelled'] ?? 0 ?></h4>
                    <small class="text-muted">Cancelados</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <h4 class="fw-bold mb-0 text-primary">R$ <?= number_format($summary['total_revenue'] ?? 0, 2, ',', '.') ?></h4>
                    <small class="text-muted">Faturamento</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="mb-3">Serviços mais procurados</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topServices as $s): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= e($s['name']) ?> <span class="badge bg-primary"><?= $s['total'] ?></span>
                        </li>
                        <?php endforeach; ?>
                        <?php if (empty($topServices)): ?>
                        <li class="list-group-item text-muted">Sem dados no período.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="mb-3">Clientes mais frequentes</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topClients as $c): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= e($c['name']) ?> <span class="badge bg-secondary"><?= $c['total'] ?></span>
                        </li>
                        <?php endforeach; ?>
                        <?php if (empty($topClients)): ?>
                        <li class="list-group-item text-muted">Sem dados no período.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>