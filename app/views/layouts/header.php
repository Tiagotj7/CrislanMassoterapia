<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= e($title ?? 'Crislan Massoterapeuta') ?></title>
    <meta name="description" content="<?= e($description ?? 'Massoterapia esportiva profissional. Agende seu horário online em menos de 1 minuto.') ?>">
    <meta name="author" content="Crislan Massoterapeuta">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= BASE_URL ?><?= $_SERVER['REQUEST_URI'] ?? '' ?>">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($title ?? 'Crislan Massoterapeuta') ?>">
    <meta property="og:description" content="<?= e($description ?? 'Agende sua massagem esportiva online.') ?>">
    <meta property="og:image" content="<?= asset('img/og-image.jpg') ?>">
    <meta property="og:url" content="<?= BASE_URL ?>">
    <meta property="og:locale" content="pt_BR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($title ?? 'Crislan Massoterapeuta') ?>">
    <meta name="twitter:description" content="<?= e($description ?? '') ?>">
    <meta name="twitter:image" content="<?= asset('img/og-image.jpg') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= asset('img/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= url('manifest.json') ?>">
    <meta name="theme-color" content="#006BFF">

    <!-- Preconnect para performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.min.css') ?>">

    <!-- Dados estruturados (Schema.org) para SEO local -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HealthAndBeautyBusiness",
        "name": "Crislan Massoterapeuta",
        "image": "<?= asset('img/og-image.jpg') ?>",
        "telephone": "<?= e($settings['whatsapp'] ?? '') ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= e($settings['address'] ?? '') ?>"
        },
        "priceRange": "$$",
        "openingHours": "Mo-Sa <?= e($settings['opening_time'] ?? '08:00') ?>-<?= e($settings['closing_time'] ?? '19:00') ?>"
    }
    </script>

    <script>window.BASE_URL = "<?= BASE_URL ?>";</script>
</head>
<body>