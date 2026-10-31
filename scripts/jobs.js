"use strict";

document.addEventListener('DOMContentLoaded', function () {
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

    const heroCounters = document.querySelectorAll('.page-hero .counter');
    heroCounters.forEach(function (counter) {
        animateCounter(counter);
    });

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -30px 0px',
        threshold: 0.08
    };

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');

                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(function (counter) {
                        animateCounter(counter);
                    });

                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-on-scroll').forEach(function (element) {
            revealObserver.observe(element);
        });
    } else {
        document.querySelectorAll('.reveal-on-scroll').forEach(function (element) {
            element.classList.add('is-visible');
        });
    }
    const applyButtons = document.querySelectorAll('.role-apply');

    applyButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const refNum = btn.getAttribute('data-ref');
            if (refNum && typeof (Storage) !== "undefined") {
                localStorage.setItem('refnum', refNum);
            }
        });
    });

    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (!isTouchDevice) {
        const roleCards = document.querySelectorAll('.role-card');

        roleCards.forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                const rotateX = (-y / rect.height) * 4;
                const rotateY = (x / rect.width) * 4;

                card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-6px)';
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            });
        });
    }
});