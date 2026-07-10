<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><?= e($client['name']) ?></h2>
            <a href="<?= url('admin/clientes/' . $client['id'] . '/editar') ?>" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen me-1"></i> Editar
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3">
                    <small class="text-muted">Telefone</small>
                    <p class="fw-bold mb-0"><?= e($client['phone']) ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3">
                    <small class="text-muted">Total de Atendimentos</small>
                    <p class="fw-bold mb-0"><?= $client['total_appointments'] ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3">
                    <small class="text-muted">Cliente desde</small>
                    <p class="fw-bold mb-0"><?= date('d/m/Y', strtotime($client['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <?php if ($client['notes']): ?>
        <div class="card border-0 shadow-sm p-3 mb-4">
            <h6>Observações</h6>
            <p class="mb-0"><?= nl2br(e($client['notes'])) ?></p>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-3">
            <h5 class="mb-3">Histórico de Agendamentos</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Serviço</th>
                            <th>Preço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($h['appointment_date'])) ?></td>
                            <td><?= date('H:i', strtotime($h['appointment_time'])) ?></td>
                            <td><?= e($h['service_name']) ?></td>
                            <td>R$ <?= number_format($h['price'], 2, ',', '.') ?></td>
                            <td><span class="badge bg-secondary"><?= ucfirst($h['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($history)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">Nenhum atendimento registrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>