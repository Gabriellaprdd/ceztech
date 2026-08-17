"use strict";

(function () {
    var wordInterval = null;

    function initDynamicIntro() {
        var introEl = document.getElementById("intro-text-target") || document.getElementById("intro");
        if (!introEl) return;

        if (wordInterval) {
            clearInterval(wordInterval);
            wordInterval = null;
        }

        if (window.location.hash === "#intro-enhancement" || window.location.hash === "#intro-text-target") {
            introEl.innerHTML = 'Tell us about your <span id="dynamic-word" class="cycling-word">passion</span> and the role that interests you. ' +
                                '<strong style="color: var(--pink-700);">You have exactly 60 seconds</strong> to complete this form before it locks.';

            var words = ["passion", "skills", "background", "experience"];
            var wordIndex = 0;

            wordInterval = setInterval(function () {
                var dynamicWordEl = document.getElementById("dynamic-word");
                if (!dynamicWordEl) return;

                dynamicWordEl.classList.add("hidden");
                
                setTimeout(function () {
                    if (!dynamicWordEl) return;
                    wordIndex = (wordIndex + 1) % words.length;
                    dynamicWordEl.textContent = words[wordIndex];
                    dynamicWordEl.classList.remove("hidden");
                }, 400); 
            }, 2500);

        } else {
            introEl.innerHTML = "Tell us about your background, strengths and the role that interests you. Your information will be submitted securely to the CezTech recruitment team.";
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initDynamicIntro);
    } else {
        initDynamicIntro();
    }

    window.addEventListener("hashchange", initDynamicIntro);
})();

document.addEventListener('DOMContentLoaded', function () {
    function animateCounter(counterElement) {
        if (!counterElement || counterElement.classList.contains('counted')) return;
        counterElement.classList.add('counted');

        const target = parseInt(counterElement.getAttribute('data-target'), 10);
        if (isNaN(target)) return;

        const duration = 1200;
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

    const summaryCounters = document.querySelectorAll('.enhancement-summary .counter');
    summaryCounters.forEach(function (counter) {
        animateCounter(counter);
    });

    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -20px 0px',
        threshold: 0.1
    };

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
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

    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (!isTouchDevice) {
        const spotlightCards = document.querySelectorAll('.js-spotlight-card');

        spotlightCards.forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                card.style.setProperty('--mouse-x', x + 'px');
                card.style.setProperty('--mouse-y', y + 'px');
            });
        });

        const interactiveCards = document.querySelectorAll('.enhancement-card--server, .implementation-note');

        interactiveCards.forEach(function (card) {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                const rotateX = (-y / rect.height) * 4.5;
                const rotateY = (x / rect.width) * 4.5;

                card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-5px)';
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            });
        });
    }
});