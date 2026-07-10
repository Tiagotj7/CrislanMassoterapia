<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Painel Administrativo') ?></title>
    <meta name="robots" content="noindex, nofollow">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <script>window.BASE_URL = "<?= BASE_URL ?>";</script>
</head>
<body class="admin-body">