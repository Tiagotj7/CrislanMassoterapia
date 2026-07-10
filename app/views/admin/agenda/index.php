<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Agenda</h2>

            <div class="btn-group">
                <a href="?view=day&date=<?= $date ?>" class="btn btn-outline-primary <?= $view === 'day' ? 'active' : '' ?>">Diário</a>
                <a href="?view=week&date=<?= $date ?>" class="btn btn-outline-primary <?= $view === 'week' ? 'active' : '' ?>">Semanal</a>
                <a href="?view=month&date=<?= $date ?>" class="btn btn-outline-primary <?= $view === 'month' ? 'active' : '' ?>">Mensal</a>
            </div>

            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#blockModal">
                <i class="fa-solid fa-ban me-1"></i> Bloquear datas
            </button>
        </div>

        <form method="GET" class="mb-4 d-flex gap-2 align-items-center">
            <input type="hidden" name="view" value="<?= e($view) ?>">
            <input type="date" name="date" value="<?= e($date) ?>" class="form-control" style="max-width: 200px;">
            <button class="btn btn-outline-secondary">Ir</button>
        </form>

        <div class="card border-0 shadow-sm p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="appointments-table">
                    <thead>
                        <tr>
                            <?php if ($view !== 'day'): ?><th>Data</th><?php endif; ?>
                            <th>Hora</th>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <th>Serviço</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $a): ?>
                        <tr data-id="<?= $a['id'] ?>">
                            <?php if ($view !== 'day'): ?>
                                <td><?= date('d/m/Y', strtotime($a['appointment_date'])) ?></td>
                            <?php endif; ?>
                            <td><?= date('H:i', strtotime($a['appointment_time'])) ?></td>
                            <td><?= e($a['client_name']) ?></td>
                            <td><?= e($a['client_phone'] ?? '-') ?></td>
                            <td><?= e($a['service_name']) ?></td>
                            <td>
                                <select class="form-select form-select-sm status-select" data-id="<?= $a['id'] ?>">
                                    <?php foreach (['pendente','confirmado','concluido','cancelado'] as $status): ?>
                                        <option value="<?= $status ?>" <?= $a['status'] === $status ? 'selected' : '' ?>>
                                            <?= ucfirst($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $a['id'] ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($appointments)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Nenhum agendamento neste período.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bloqueios ativos -->
        <div class="card border-0 shadow-sm p-3 mt-4">
            <h5 class="mb-3">Datas bloqueadas</h5>
            <ul class="list-group list-group-flush">
                <?php foreach ($blockedDates as $b): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= date('d/m/Y', strtotime($b['blocked_date'])) ?> — <?= e($b['reason'] ?? 'Sem motivo') ?></span>
                    <button class="btn btn-sm btn-outline-danger btn-unblock" data-id="<?= $b['id'] ?>">Remover</button>
                </li>
                <?php endforeach; ?>
                <?php if (empty($blockedDates)): ?>
                <li class="list-group-item text-muted">Nenhuma data bloqueada.</li>
                <?php endif; ?>
            </ul>
        </div>
    </main>
</div>

<!-- Modal de bloqueio -->
<div class="modal fade" id="blockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="block-form">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Bloquear período</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Data inicial</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data final (opcional, para férias)</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <input type="text" name="reason" class="form-control" placeholder="Ex: Férias, feriado...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Bloquear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>