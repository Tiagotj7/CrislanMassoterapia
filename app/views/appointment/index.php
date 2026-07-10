<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<main class="container py-5">
    <h1 class="text-center mb-4 fw-bold">Agende seu horário</h1>

    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 560px;" id="booking-card">
        <!-- Etapa 1: Serviço -->
        <div class="booking-step" data-step="1">
            <h5 class="mb-3">1. Escolha o serviço</h5>
            <?php foreach ($services as $service): ?>
                <div class="form-check service-option mb-2 p-3 border rounded">
                    <input class="form-check-input" type="radio" name="service_id"
                           id="service-<?= $service['id'] ?>" value="<?= $service['id'] ?>">
                    <label class="form-check-label w-100" for="service-<?= $service['id'] ?>">
                        <strong><?= e($service['name']) ?></strong>
                        <span class="d-block text-muted small">
                            <?= (int) $service['duration_minutes'] ?> min · R$ <?= number_format($service['price'], 2, ',', '.') ?>
                        </span>
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="button" class="btn btn-primary w-100 mt-3" id="btn-step-1">Continuar</button>
        </div>

        <!-- Etapa 2: Data e horário -->
        <div class="booking-step d-none" data-step="2">
            <h5 class="mb-3">2. Escolha a data e horário</h5>
            <input type="date" class="form-control mb-3" id="appointment-date" min="<?= date('Y-m-d') ?>">
            <div id="slots-container" class="d-flex flex-wrap gap-2"></div>
            <button type="button" class="btn btn-outline-secondary mt-3" data-back="1">Voltar</button>
        </div>

        <!-- Etapa 3: Dados do cliente -->
        <div class="booking-step d-none" data-step="3">
            <h5 class="mb-3">3. Seus dados</h5>
            <form id="booking-form">
                <?= csrf_field() ?>
                <input type="hidden" name="service_id" id="input-service-id">
                <input type="hidden" name="date" id="input-date">
                <input type="hidden" name="time" id="input-time">

                <div class="mb-3">
                    <label class="form-label">Nome completo</label>
                    <input type="text" class="form-control" name="name" required minlength="3">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone (WhatsApp)</label>
                    <input type="tel" class="form-control" name="phone" required minlength="10" placeholder="(00) 00000-0000">
                </div>
                <div class="mb-3">
                    <label class="form-label">Observações (opcional)</label>
                    <textarea class="form-control" name="notes" rows="2"></textarea>
                </div>

                <div id="form-feedback" class="alert d-none" role="alert"></div>

                <button type="submit" class="btn btn-primary w-100" id="btn-submit">
                    <span class="btn-text">Confirmar Agendamento</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <button type="button" class="btn btn-outline-secondary w-100 mt-2" data-back="2">Voltar</button>
            </form>
        </div>
    </div>
</main>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>