    <!-- ========== RODAPÉ ========== -->
    <footer class="footer-crislan pt-5 pb-4 text-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3">Crislan Massoterapeuta</h5>
                    <p class="text-white-50 small">
                        Massoterapia esportiva profissional, focada em recuperação muscular
                        e bem-estar para atletas e pessoas ativas.
                    </p>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3">Links Rápidos</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#sobre">Sobre</a></li>
                        <li><a href="#servicos">Serviços</a></li>
                        <li><a href="#depoimentos">Depoimentos</a></li>
                        <li><a href="<?= tenant_url('agendamento') ?>">Agendar</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3">Redes Sociais</h6>
                    <div class="d-flex gap-3">
                        <a href="https://wa.me/<?= e($settings['whatsapp'] ?? '') ?>" class="social-icon" target="_blank" rel="noopener" aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <?php if (!empty($settings['instagram'])): ?>
                        <a href="<?= e($settings['instagram']) ?>" class="social-icon" target="_blank" rel="noopener" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-white-50 small">
                &copy; <?= date('Y') ?> Crislan Massoterapeuta. Todos os direitos reservados.
                <br>Desenvolvido por <strong>Impact</strong>.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js" defer></script>
    <script src="<?= asset('js/home.min.js') ?>" defer></script>
</body>
</html>