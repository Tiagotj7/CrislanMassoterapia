<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-3" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('/') ?>">
            <?php if (!empty($settings['logo'])): ?>
                <img src="<?= url('uploads/' . $settings['logo']) ?>" alt="Crislan Massoterapeuta" height="40">
            <?php else: ?>
                <span class="brand-text">CRISLAN</span>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
                <li class="nav-item"><a class="nav-link" href="#servicos">Serviços</a></li>
                <li class="nav-item"><a class="nav-link" href="#depoimentos">Depoimentos</a></li>
                <li class="nav-item"><a class="nav-link" href="#contato">Contato</a></li>
                <li class="nav-item">
                    <a class="btn btn-primary rounded-pill px-4" href="<?= url('agendamento') ?>">Agendar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ========== HERO ========== -->
<header class="hero-section d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right">
                <span class="badge-hero mb-3">Massoterapia Esportiva Profissional</span>
                <h1 class="hero-title mb-4">
                    Recupere seu <span class="text-gradient">corpo</span>,<br>
                    eleve sua <span class="text-gradient">performance</span>
                </h1>
                <p class="hero-subtitle mb-4">
                    Atendimento especializado para atletas e pessoas ativas.
                    Alívio de dores, recuperação muscular e bem-estar em um só lugar.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= tenant_url('agendamento') ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                        <i class="fa-solid fa-calendar-check me-2"></i>Agendar Horário
                    </a>
                    <a href="https://wa.me/<?= e($settings['whatsapp'] ?? '') ?>" target="_blank" rel="noopener" class="btn btn-outline-success btn-lg rounded-pill px-4">
                        <i class="fa-brands fa-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>

                <div class="d-flex gap-4 mt-5">
                    <div>
                        <h3 class="fw-bold mb-0">+500</h3>
                        <small class="text-muted">Atendimentos</small>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">5.0 <i class="fa-solid fa-star text-warning small"></i></h3>
                        <small class="text-muted">Avaliação</small>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">+8</h3>
                        <small class="text-muted">Anos de experiência</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left">
                <div class="hero-image-wrapper">
                    <?php if (!empty($settings['photo'])): ?>
                        <img src="<?= url('uploads/' . $settings['photo']) ?>"
                             alt="Crislan Massoterapeuta"
                             class="hero-image img-fluid"
                             fetchpriority="high">
                    <?php else: ?>
                        <img src="<?= asset('img/placeholder-hero.webp') ?>"
                             alt="Crislan Massoterapeuta"
                             class="hero-image img-fluid">
                    <?php endif; ?>
                    <div class="hero-shape"></div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ========== SOBRE ========== -->
<section id="sobre" class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <img src="<?= asset('img/sobre.webp') ?>" alt="Sobre Crislan" class="img-fluid rounded-4 shadow" loading="lazy">
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <span class="section-label">Quem sou eu</span>
                <h2 class="section-title mb-4">Especialista em Massoterapia Esportiva</h2>
                <p class="text-muted mb-4">
                    Com mais de 8 anos de experiência, dedico minha carreira ao cuidado do corpo de
                    atletas e pessoas que buscam alívio de dores musculares, recuperação e qualidade de vida.
                    Minha abordagem combina técnicas avançadas de massoterapia esportiva com atenção
                    individualizada a cada cliente.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="feature-item">
                            <i class="fa-solid fa-medal text-primary"></i>
                            <span>Certificado em Massoterapia Esportiva</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-item">
                            <i class="fa-solid fa-house-medical text-primary"></i>
                            <span>Atendimento Domiciliar</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-item">
                            <i class="fa-solid fa-person-running text-primary"></i>
                            <span>Foco em Atletas</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-item">
                            <i class="fa-solid fa-heart-pulse text-primary"></i>
                            <span>Técnicas de Recuperação</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SERVIÇOS ========== -->
<section id="servicos" class="section-padding bg-light-soft">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">O que eu ofereço</span>
            <h2 class="section-title">Nossos Serviços</h2>
            <p class="text-muted">Escolha o tratamento ideal para suas necessidades</p>
        </div>

        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="service-card h-100">
                    <div class="service-image">
                        <?php if ($service['image']): ?>
                            <img src="<?= url('uploads/' . $service['image']) ?>" alt="<?= e($service['name']) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="service-placeholder">
                                <i class="fa-solid fa-spa"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="service-body">
                        <h5><?= e($service['name']) ?></h5>
                        <p class="text-muted small"><?= e($service['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="service-meta">
                                <i class="fa-regular fa-clock me-1"></i><?= (int) $service['duration_minutes'] ?> min
                            </span>
                            <span class="service-price">R$ <?= number_format($service['price'], 2, ',', '.') ?></span>
                        </div>
                        <a href="<?= tenant_url('agendamento') ?>" class="btn btn-primary w-100 rounded-pill mt-3">
                            Agendar
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($services)): ?>
            <div class="col-12 text-center text-muted py-5">
                <p>Em breve, novos serviços disponíveis.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== BENEFÍCIOS ========== -->
<section class="section-padding bg-dark-gradient text-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label text-light">Diferenciais</span>
            <h2 class="section-title text-white">Por que me escolher</h2>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="0">
                <div class="benefit-icon"><i class="fa-solid fa-person-running"></i></div>
                <h6 class="mt-3">Especialista em Atletas</h6>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-icon"><i class="fa-solid fa-house"></i></div>
                <h6 class="mt-3">Atendimento Domiciliar</h6>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-icon"><i class="fa-solid fa-chart-line"></i></div>
                <h6 class="mt-3">Resultados Comprovados</h6>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-icon"><i class="fa-solid fa-shield-heart"></i></div>
                <h6 class="mt-3">Cuidado Individualizado</h6>
            </div>
        </div>
    </div>
</section>

<!-- ========== DEPOIMENTOS ========== -->
<?php if (!empty($testimonials)): ?>
<section id="depoimentos" class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">Depoimentos</span>
            <h2 class="section-title">O que dizem meus clientes</h2>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-md-4" data-aos="fade-up">
                <div class="testimonial-card h-100">
                    <div class="stars mb-2">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i class="fa-solid fa-star <?= $i < $t['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="fst-italic">"<?= e($t['comment']) ?>"</p>
                    <strong>— <?= e($t['client_name']) ?></strong>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== GALERIA ========== -->
<?php if (!empty($gallery)): ?>
<section class="section-padding bg-light-soft">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">Galeria</span>
            <h2 class="section-title">Ambiente e Atendimentos</h2>
        </div>
        <div class="row g-3">
            <?php foreach ($gallery as $item): ?>
            <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in">
                <div class="gallery-item">
                    <img src="<?= url('uploads/' . $item['image']) ?>" alt="<?= e($item['title'] ?? 'Galeria') ?>" loading="lazy">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========== CONTATO / MAPA ========== -->
<section id="contato" class="section-padding bg-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="section-label">Contato</span>
                <h2 class="section-title mb-4">Vamos agendar sua sessão?</h2>
                <p class="text-muted mb-4"><?= e($settings['address'] ?? '') ?></p>

                <div class="d-flex flex-column gap-3">
                    <a href="https://wa.me/<?= e($settings['whatsapp'] ?? '') ?>" target="_blank" rel="noopener" class="contact-link">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                    </a>
                    <?php if (!empty($settings['instagram'])): ?>
                    <a href="<?= e($settings['instagram']) ?>" target="_blank" rel="noopener" class="contact-link">
                        <i class="fa-brands fa-instagram"></i> Instagram
                    </a>
                    <?php endif; ?>
                    <span class="contact-link">
                        <i class="fa-regular fa-clock"></i>
                        <?= e($settings['opening_time'] ?? '08:00') ?> às <?= e($settings['closing_time'] ?? '19:00') ?>
                    </span>
                </div>

                <a href="<?= tenant_url('agendamento') ?>" class="btn btn-primary btn-lg rounded-pill mt-4 px-5">
                    Agendar Agora
                </a>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <?php if (!empty($settings['google_maps_embed'])): ?>
                    <div class="map-wrapper rounded-4 overflow-hidden shadow">
                        <?= $settings['google_maps_embed'] // conteúdo controlado apenas pelo admin autenticado ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ========== CTA FINAL ========== -->
<section class="cta-final text-center text-white">
    <div class="container py-5">
        <h2 class="fw-bold mb-3">Pronto para cuidar do seu corpo?</h2>
        <p class="mb-4">Agende agora e garanta seu horário</p>
        <a href="<?= tenant_url('agendamento') ?>" class="btn btn-light btn-lg rounded-pill px-5 fw-bold">
            Agendar Horário
        </a>
    </div>
</section>

<!-- ========== WHATSAPP FLUTUANTE ========== -->
<a href="https://wa.me/<?= e($settings['whatsapp'] ?? '') ?>" target="_blank" rel="noopener" class="whatsapp-float" aria-label="Fale pelo WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>