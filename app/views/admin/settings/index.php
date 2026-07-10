<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>
<?php use App\Core\Session; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4">Configurações</h2>

        <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="<?= url('admin/configuracoes/atualizar') ?>">
            <?= csrf_field() ?>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h5 class="mb-3">Contato</h5>

                        <div class="mb-3">
                            <label class="form-label">WhatsApp (com DDD)</label>
                            <input type="text" name="whatsapp" class="form-control"
                                   value="<?= e($settings['whatsapp'] ?? '') ?>" placeholder="5599999999999">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instagram (URL completa)</label>
                            <input type="url" name="instagram" class="form-control"
                                   value="<?= e($settings['instagram'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endereço</label>
                            <textarea name="address" class="form-control" rows="2"><?= e($settings['address'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Google Maps (código de incorporação/iframe)</label>
                            <textarea name="google_maps_embed" class="form-control" rows="3"><?= e($settings['google_maps_embed'] ?? '') ?></textarea>
                            <small class="text-muted">Cole o código &lt;iframe&gt; do Google Maps aqui.</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h5 class="mb-3">Horário de Funcionamento</h5>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Abertura</label>
                                <input type="time" name="opening_time" class="form-control"
                                       value="<?= e($settings['opening_time'] ?? '08:00') ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Fechamento</label>
                                <input type="time" name="closing_time" class="form-control"
                                       value="<?= e($settings['closing_time'] ?? '19:00') ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Início almoço</label>
                                <input type="time" name="lunch_start" class="form-control"
                                       value="<?= e($settings['lunch_start'] ?? '12:00') ?>">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Fim almoço</label>
                                <input type="time" name="lunch_end" class="form-control"
                                       value="<?= e($settings['lunch_end'] ?? '13:30') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Intervalo entre horários (minutos)</label>
                            <input type="number" name="slot_interval_minutes" class="form-control" min="15" step="5"
                                   value="<?= e($settings['slot_interval_minutes'] ?? '60') ?>">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="works_sunday" class="form-check-input" id="works_sunday"
                                   <?= ($settings['works_sunday'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="works_sunday">Atender aos domingos</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="mb-3">Identidade Visual</h5>

                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <?php if (!empty($settings['logo'])): ?>
                                <img src="<?= url('uploads/' . $settings['logo']) ?>" class="mt-2 rounded" style="max-width:120px;">
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto de perfil (Hero)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            <?php if (!empty($settings['photo'])): ?>
                                <img src="<?= url('uploads/' . $settings['photo']) ?>" class="mt-2 rounded" style="max-width:120px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="mb-3">Mensagem Automática</h5>
                        <textarea name="auto_message" class="form-control" rows="4"
                                  placeholder="Mensagem enviada após confirmação do agendamento"><?= e($settings['auto_message'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 px-5">Salvar Configurações</button>
        </form>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>