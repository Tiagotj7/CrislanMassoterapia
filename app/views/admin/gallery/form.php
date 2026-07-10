<?php require ROOT_PATH . '/app/views/admin/layouts/header.php'; ?>

<div class="d-flex">
    <?php require ROOT_PATH . '/app/views/admin/layouts/sidebar.php'; ?>

    <main class="admin-content flex-grow-1 p-4">
        <h2 class="fw-bold mb-4">Nova Imagem</h2>

        <div class="card border-0 shadow-sm p-4" style="max-width: 500px;">
            <form method="POST" enctype="multipart/form-data" action="<?= url('admin/galeria/salvar') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Imagem *</label>
                    <input type="file" name="image" class="form-control" required accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="mb-3">
                    <label class="form-label">Título (opcional)</label>
                    <input type="text" name="title" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ordem de exibição</label>
                    <input type="number" name="order_position" class="form-control" value="0">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="<?= url('admin/galeria') ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require ROOT_PATH . '/app/views/admin/layouts/footer.php'; ?>