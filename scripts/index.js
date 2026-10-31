"use strict";

document.addEventListener('DOMContentLoaded', function () {
const observerOptions = {
root: null,
rootMargin: '0px 0px -40px 0px',
threshold: 0.12
};

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');

                const counters = entry.target.querySelectorAll('.counter');
                if (counters.length > 0) {
                    counters.forEach(function (counter) {
                        animateCounter(counter);
                    });
                }

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

function animateCounter(counterElement) {
    if (!counterElement || counterElement.classList.contains('counted')) return;
    counterElement.classList.add('counted');

    const target = parseInt(counterElement.getAttribute('data-target'), 10);
    if (isNaN(target)) return;

    const duration = 1200;
    const start = 0;
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeProgress = progress * (2 - progress);
        const currentCount = Math.floor(easeProgress * (target - start) + start);

        counterElement.textContent = currentCount;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            counterElement.textContent = target;
        }
    }

    requestAnimationFrame(update);
}

const cycler = document.getElementById('hero-word-cycler');
if (cycler) {
    const words = ['people', 'businesses', 'innovators', 'teams'];
    let index = 0;

    setInterval(function () {
        cycler.classList.add('hidden');
        setTimeout(function () {
            index = (index + 1) % words.length;
            cycler.textContent = words[index];
            cycler.classList.remove('hidden');
        }, 300);
    }, 3000);
}

const spotlightCards = document.querySelectorAll('.value-card, .contact-card');

spotlightCards.forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        card.style.setProperty('--mouse-x', x + 'px');
        card.style.setProperty('--mouse-y', y + 'px');
    });
});

const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

if (!isTouchDevice) {
    const tiltCards = document.querySelectorAll('.value-card, .contact-card, .floating-card');

    tiltCards.forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            const rotateX = (-y / rect.height) * 8;
            const rotateY = (x / rect.width) * 8;

            card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-4px)';
        });

        card.addEventListener('mouseleave', function () {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
        });
    });
}

window.addEventListener('orientationchange', function () {
    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 200);
});

});