<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<main class="container py-5 text-center">
    <i class="fa-solid fa-circle-check text-success" style="font-size: 4rem;"></i>
    <h1 class="mt-3">Agendamento confirmado!</h1>
    <p class="text-muted">Em breve você receberá a confirmação. Chegue com 10 minutos de antecedência.</p>
    <a href="<?= url('/') ?>" class="btn btn-primary mt-3">Voltar para o início</a>
</main>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>