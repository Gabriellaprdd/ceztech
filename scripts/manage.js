"use strict";

function dismissToast(btn) {
    var toast = btn ? btn.closest('.toast-notification') : null;
    if (toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px) scale(0.95)';
        setTimeout(function() { toast.remove(); }, 300);
    }
}
window.dismissToast = dismissToast;

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function() {
        var toasts = document.querySelectorAll('.toast-notification');
        toasts.forEach(function(toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px) scale(0.95)';
            setTimeout(function() { toast.remove(); }, 300);
        });
    }, 6000);

    function animateCounter(counterElement) {
        if (!counterElement || counterElement.classList.contains('counted')) return;
        counterElement.classList.add('counted');

        const target = parseInt(counterElement.getAttribute('data-target'), 10);
        if (isNaN(target)) return;

        const duration = 1100;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = progress * (2 - progress);
            const currentCount = Math.floor(easeProgress * target);

            counterElement.textContent = currentCount;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                counterElement.textContent = target;
            }
        }

        requestAnimationFrame(update);
    }

    const statCounters = document.querySelectorAll('.manager-stats .counter');
    statCounters.forEach(function(counter) {
        animateCounter(counter);
    });

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -20px 0px',
        threshold: 0.08
    };

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-on-scroll').forEach(function(element) {
            revealObserver.observe(element);
        });
    } else {
        document.querySelectorAll('.reveal-on-scroll').forEach(function(element) {
            element.classList.add('is-visible');
        });
    }

    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (!isTouchDevice) {
        const tiltCards = document.querySelectorAll('.manager-account-card, .manager-stats article, .manager-tool-card');

        tiltCards.forEach(function(card) {
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                const rotateX = (-y / rect.height) * 4;
                const rotateY = (x / rect.width) * 4;

                card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-4px)';
            });

            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            });
        });
    }
});