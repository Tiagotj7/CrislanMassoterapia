<?php
/** @var int $todayCount */
/** @var array|null $nextAppointment */
/** @var array $weekAppointments */
/** @var array $monthlyStats */
require ROOT_PATH . '/app/views/admin/layouts/header.php';
?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4">Dashboard</h2>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <i class="fa-solid fa-calendar-check text-primary fs-2 mb-2"></i>
                    <h3 class="fw-bold mb-0"><?= $todayCount ?></h3>
                    <small class="text-muted">Agendamentos hoje</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 text-center">
                    <i class="fa-solid fa-clock text-primary fs-2 mb-2"></i>
                    <?php if ($nextAppointment): ?>
                        <h6 class="fw-bold mb-0"><?= date('H:i', strtotime($nextAppointment['appointment_time'])) ?></h6>
                        <small class="text-muted"><?= e($nextAppointment['client_name']) ?></small>
                    <?php else: ?>
                        <h6 class="text-muted mb-0">Nenhum</h6>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="mb-3">Próximos 7 dias</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Serviço</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekAppointments as $a): ?>
                                <tr>
                                    <td><?= date('d/m', strtotime($a['appointment_date'])) ?></td>
                                    <td><?= date('H:i', strtotime($a['appointment_time'])) ?></td>
                                    <td><?= e($a['client_name']) ?></td>
                                    <td><?= e($a['service_name']) ?></td>
                                    <td><span class="badge bg-<?= $a['status'] === 'confirmado' ? 'success' : 'warning' ?>"><?= e($a['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($weekAppointments)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">Nenhum agendamento nos próximos dias.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3">
                    <h5 class="mb-3">Atendimentos por mês</h5>
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const chartLabels = <?= json_encode(array_column($monthlyStats, 'month')) ?>;
    const chartData = <?= json_encode(array_column($monthlyStats, 'total')) ?>;

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Atendimentos',
                data: chartData,
                backgroundColor: '#006BFF'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>