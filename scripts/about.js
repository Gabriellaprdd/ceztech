document.addEventListener('DOMContentLoaded', () => {
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!isTouchDevice && !prefersReducedMotion) {
        const tiltCards = document.querySelectorAll('.about-hero, .academic-card, .hometown-panel, .interest-card');

        tiltCards.forEach(card => {
            let rect = null;
            let rafId = null;

            card.addEventListener('mouseenter', () => {
                rect = card.getBoundingClientRect();
            }, { passive: true });

            card.addEventListener('mousemove', (e) => {
                if (!rect) rect = card.getBoundingClientRect();

                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                const rotateX = (-y / rect.height) * 4;
                const rotateY = (x / rect.width) * 4;

                if (rafId) cancelAnimationFrame(rafId);

                rafId = requestAnimationFrame(() => {
                    card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-4px)`;
                });
            }, { passive: true });

            card.addEventListener('mouseleave', () => {
                if (rafId) cancelAnimationFrame(rafId);
                rect = null;
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            }, { passive: true });
        });

        window.addEventListener('resize', () => {
            tiltCards.forEach(card => { card._rect = null; });
        }, { passive: true });
    }
});