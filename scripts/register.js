"use strict";

function togglePasswordVisibility(fieldId, btn) {
    var input = document.getElementById(fieldId);
    if (!input) return;
    
    var isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";

    if (isPassword) {
        btn.classList.add("showing");
        btn.innerHTML = '<svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    } else {
        btn.classList.remove("showing");
        btn.innerHTML = '<svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    }
}
window.togglePasswordVisibility = togglePasswordVisibility;

function dismissToast(btn) {
    var toast = btn ? btn.closest('.toast-notification') : null;
    if (toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px) scale(0.95)';
        setTimeout(function() { toast.remove(); }, 300);
    }
}
window.dismissToast = dismissToast;

function setupLiveFormatting() {
    var username = document.getElementById("username");
    if (username) {
        username.addEventListener("input", function() {
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '').slice(0, 20);
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    setupLiveFormatting();
    
    setTimeout(function() {
        var toasts = document.querySelectorAll('.toast-notification');
        toasts.forEach(function(toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px) scale(0.95)';
            setTimeout(function() { toast.remove(); }, 300);
        });
    }, 6000);

    var isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0 || window.matchMedia("(pointer: coarse)").matches;

    if (!isTouchDevice) {
        var card = document.querySelector('.login-card');
        if (card) {
            card.addEventListener('mousemove', function(e) {
                var rect = card.getBoundingClientRect();
                var x = e.clientX - rect.left - rect.width / 2;
                var y = e.clientY - rect.top - rect.height / 2;

                var rotateX = (-y / rect.height) * 4;
                var rotateY = (x / rect.width) * 4;

                card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-4px)';
            });

            card.addEventListener('mouseleave', function() {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
            });
        }
    }
});