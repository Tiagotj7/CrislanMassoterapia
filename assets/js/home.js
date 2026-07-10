document.addEventListener('DOMContentLoaded', () => {
    // Inicializa animações AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 700,
            once: true,
            offset: 80,
        });
    }

    // Navbar com efeito de scroll
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('shadow', window.scrollY > 20);
        });
    }

    // Scroll suave para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Fecha o menu mobile após clicar
                const navCollapse = document.getElementById('navMenu');
                if (navCollapse?.classList.contains('show')) {
                    bootstrap.Collapse.getInstance(navCollapse)?.hide();
                }
            }
        });
    });

    // Lazy load nativo de fallback (para navegadores antigos)
    if ('loading' in HTMLImageElement.prototype === false) {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }
});