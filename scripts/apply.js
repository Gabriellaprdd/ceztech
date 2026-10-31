"use strict";

var debug = false;
var timeLeft = 60;
var timerInterval = null;

function dismissToast(btn) {
    var toast = btn ? btn.closest('.toast-notification') : null;
    if (toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px) scale(0.95)';
        setTimeout(function() { toast.remove(); }, 300);
    }
}
window.dismissToast = dismissToast;

function startTimer() {
    clearInterval(timerInterval);
    timeLeft = 60;
    
    var timerDisplay = document.getElementById("timer-display");
    var submitBtn = document.getElementById("submitBtn");

    if (submitBtn) submitBtn.disabled = false;

    timerInterval = setInterval(function () {
        timeLeft--;
        if (timerDisplay) {
            timerDisplay.textContent = timeLeft;
        }

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            if (submitBtn) submitBtn.disabled = true;
            showTimerModal();
        }
    }, 1000);
}

function showTimerModal() {
    var modal = document.getElementById("timerModal");
    if (modal) {
        modal.classList.add("active");
        modal.setAttribute("aria-hidden", "false");
    }
}

function hideTimerModal() {
    var modal = document.getElementById("timerModal");
    if (modal) {
        modal.classList.remove("active");
        modal.setAttribute("aria-hidden", "true");
    }
}

function setupLiveFormatting() {
    var firstname = document.getElementById("firstname");
    var lastname = document.getElementById("lastname");
    var address = document.getElementById("address");
    var suburb = document.getElementById("suburb");
    var postcode = document.getElementById("postcode");
    var number = document.getElementById("number");
    var email = document.getElementById("email");

    if (firstname) {
        firstname.addEventListener("input", function () {
            var val = this.value.replace(/[^A-Za-z]/g, '');
            if (val.length > 0) {
                val = val.charAt(0).toUpperCase() + val.slice(1);
            }
            this.value = val;
        });
    }

    if (lastname) {
        lastname.addEventListener("input", function () {
            var val = this.value.replace(/[^A-Za-z]/g, '');
            if (val.length > 0) {
                val = val.charAt(0).toUpperCase() + val.slice(1);
            }
            this.value = val;
        });
    }

    if (suburb) {
        suburb.addEventListener("input", function () {
            var val = this.value.replace(/[^A-Za-z\s]/g, '');
            val = val.replace(/(^\w|\s\w)/g, function (letter) {
                return letter.toUpperCase();
            });
            this.value = val;
        });
    }

    if (address) {
        address.addEventListener("input", function () {
            var val = this.value.replace(/[^A-Za-z0-9\s\/\.\,\-]/g, '');
            val = val.replace(/(^\w|\s\w)/g, function (letter) {
                return letter.toUpperCase();
            });
            this.value = val;
        });
    }

    if (email) {
        email.addEventListener("input", function () {
            this.value = this.value.replace(/[^A-Za-z0-9@.]/g, '');
        });
    }

    if (postcode) {
        postcode.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    if (number) {
        number.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 12);
        });
    }
}

function validate() {
    var errorArray = [];
    var result = true;

    if (!debug) {
        var refnum = document.getElementById("refnum");
        var firstname = document.getElementById("firstname");
        var lastname = document.getElementById("lastname");
        var dob = document.getElementById("dob");
        var genderMale = document.getElementById("gender-male");
        var genderFemale = document.getElementById("gender-female");
        var address = document.getElementById("address");
        var suburb = document.getElementById("suburb");
        var state = document.getElementById("state");
        var postcode = document.getElementById("postcode");
        var number = document.getElementById("number");
        var email = document.getElementById("email");
        var otherSkillCheckbox = document.getElementById("otherskill");
        var otherSkillsText = document.getElementById("otherskills");

        var refVal = refnum ? refnum.value.trim() : "";
        var firstVal = firstname ? firstname.value.trim() : "";
        var lastVal = lastname ? lastname.value.trim() : "";
        var addressVal = address ? address.value.trim() : "";
        var suburbVal = suburb ? suburb.value.trim() : "";
        var postcodeVal = postcode ? postcode.value.trim() : "";
        var phoneVal = number ? number.value.trim() : "";
        var emailVal = email ? email.value.trim() : "";
        var otherSkillsVal = otherSkillsText ? otherSkillsText.value.trim() : "";

        saveInfo();

        if (timeLeft <= 0) {
            errorArray.push("Your 60-second time window has expired. Please restart the timer before submitting.");
            result = false;
        }

        if (!refVal) {
            errorArray.push("Job reference number is missing. Please select a role from the Careers page first.");
            result = false;
        } else if (!/^[A-Za-z0-9]{5}$/.test(refVal)) {
            errorArray.push("Job reference number must contain only letters and numbers (5 characters).");
            result = false;
        }

        if (!firstVal) {
            errorArray.push("First name is required.");
            result = false;
        } else if (!/^[A-Za-z]+$/.test(firstVal)) {
            errorArray.push("First name must contain letters only (no numbers, spaces, or special characters).");
            result = false;
        } else if (firstVal.length > 20) {
            errorArray.push("First name cannot exceed 20 characters.");
            result = false;
        }

        if (!lastVal) {
            errorArray.push("Last name is required.");
            result = false;
        } else if (!/^[A-Za-z]+$/.test(lastVal)) {
            errorArray.push("Last name must contain letters only (no numbers, spaces, or special characters).");
            result = false;
        } else if (lastVal.length > 20) {
            errorArray.push("Last name cannot exceed 20 characters.");
            result = false;
        }

        if (!dob || !dob.value) {
            errorArray.push("Date of birth is required.");
            result = false;
        } else {
            var birthDate = new Date(dob.value);
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (isNaN(birthDate.getTime())) {
                errorArray.push("Invalid Date of Birth.");
                result = false;
            } else if (age < 23 || age > 40) {
                errorArray.push("You must be between 23 and 40 years old.");
                result = false;
            }
        }

        if ((!genderMale || !genderMale.checked) && (!genderFemale || !genderFemale.checked)) {
            errorArray.push("Please select your gender.");
            result = false;
        }

        if (!addressVal) {
            errorArray.push("Street address is required.");
            result = false;
        } else if (!/^[A-Za-z0-9\s\/\.\,\-]+$/.test(addressVal)) {
            errorArray.push("Street address can only include numbers, letters, spaces, and .,/-");
            result = false;
        } else if (addressVal.length > 40) {
            errorArray.push("Street address cannot exceed 40 characters.");
            result = false;
        }

        if (!suburbVal) {
            errorArray.push("Suburb or town is required.");
            result = false;
        } else if (!/^[A-Za-z\s]+$/.test(suburbVal)) {
            errorArray.push("Suburb or town must contain letters and spaces only.");
            result = false;
        } else if (suburbVal.length > 40) {
            errorArray.push("Suburb or town cannot exceed 40 characters.");
            result = false;
        }

        if (!state || !state.value) {
            errorArray.push("Please select a state.");
            result = false;
        }

        if (!postcodeVal) {
            errorArray.push("Postcode is required.");
            result = false;
        } else if (!/^\d{4}$/.test(postcodeVal)) {
            errorArray.push("Postcode must be numbers only and exactly 4 digits.");
            result = false;
        } else if (state && state.value) {
            var stateOpt = state.value;
            var firstDigit = postcodeVal.charAt(0);

            var validState = false;
            if (stateOpt === "VIC" && (firstDigit === "3" || firstDigit === "8")) validState = true;
            else if (stateOpt === "NSW" && (firstDigit === "1" || firstDigit === "2")) validState = true;
            else if (stateOpt === "QLD" && (firstDigit === "4" || firstDigit === "9")) validState = true;
            else if (stateOpt === "NT" && firstDigit === "0") validState = true;
            else if (stateOpt === "WA" && firstDigit === "6") validState = true;
            else if (stateOpt === "SA" && firstDigit === "5") validState = true;
            else if (stateOpt === "TAS" && firstDigit === "7") validState = true;
            else if (stateOpt === "ACT" && firstDigit === "0") validState = true;

            if (!validState) {
                errorArray.push("Postcode (" + postcodeVal + ") does not match the selected state (" + stateOpt + ").");
                result = false;
            }
        }

        if (!phoneVal) {
            errorArray.push("Phone number is required.");
            result = false;
        } else if (!/^\d{8,12}$/.test(phoneVal)) {
            errorArray.push("Phone number must contain numbers only (8 to 12 digits).");
            result = false;
        }

        if (!emailVal) {
            errorArray.push("Email address is required.");
            result = false;
        } else if (!/^[A-Za-z0-9.@]+$/.test(emailVal) || !/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(emailVal)) {
            errorArray.push("Email address can only include numbers, letters, @, and . (e.g. user@example.com).");
            result = false;
        }

        if (otherSkillCheckbox && otherSkillCheckbox.checked && !otherSkillsVal) {
            errorArray.push("You selected 'Other relevant skills'. Please describe your skills in the text box.");
            result = false;
        }
    }

    if (errorArray.length > 0) {
        result = false;
        showErrorModal(errorArray);
    }

    return result;
}

function showErrorModal(errors) {
    var modal = document.getElementById("errorModal");
    var modalList = document.getElementById("modalErrorList");

    if (!modal || !modalList) return;

    modalList.innerHTML = "";
    errors.forEach(function (err) {
        var li = document.createElement("li");
        li.className = "modal-error-item";
        li.textContent = err;
        modalList.appendChild(li);
    });

    modal.classList.add("active");
    modal.setAttribute("aria-hidden", "false");
}

function hideErrorModal() {
    var modal = document.getElementById("errorModal");
    if (modal) {
        modal.classList.remove("active");
        modal.setAttribute("aria-hidden", "true");
    }
}

function saveInfo() {
    if (typeof (sessionStorage) !== "undefined") {
        var getVal = function (id) {
            var el = document.getElementById(id);
            return el ? el.value.trim() : "";
        };

        sessionStorage.setItem('firstname', getVal("firstname"));
        sessionStorage.setItem('lastname', getVal("lastname"));
        sessionStorage.setItem('dob', getVal("dob"));
        sessionStorage.setItem('address', getVal("address"));
        sessionStorage.setItem('suburb', getVal("suburb"));
        sessionStorage.setItem('state', getVal("state"));
        sessionStorage.setItem('postcode', getVal("postcode"));
        sessionStorage.setItem('email', getVal("email"));
        sessionStorage.setItem('number', getVal("number"));

        var gender = "";
        var maleEl = document.getElementById("gender-male");
        var femaleEl = document.getElementById("gender-female");
        if (maleEl && maleEl.checked) gender = "M";
        else if (femaleEl && femaleEl.checked) gender = "F";
        sessionStorage.setItem('gender', gender);

        var getChecked = function (id) {
            var el = document.getElementById(id);
            return el ? el.checked : false;
        };

        sessionStorage.setItem('troubleshooting', getChecked("troubleshooting"));
        sessionStorage.setItem('technicalproficiency', getChecked("technicalproficiency"));
        sessionStorage.setItem('detailoriented', getChecked("detailoriented"));
        sessionStorage.setItem('emotionalintelligence', getChecked("emotionalintelligence"));
        sessionStorage.setItem('otherskill', getChecked("otherskill"));
        sessionStorage.setItem('otherskills', getVal("otherskills"));
    }
}

function loadInfo() {
    if (typeof (sessionStorage) === "undefined") return;

    var setVal = function (id, key) {
        var el = document.getElementById(id);
        if (el && sessionStorage.getItem(key) !== null) {
            el.value = sessionStorage.getItem(key);
        }
    };

    setVal("firstname", "firstname");
    setVal("lastname", "lastname");
    setVal("dob", "dob");
    setVal("address", "address");
    setVal("suburb", "suburb");
    setVal("state", "state");
    setVal("postcode", "postcode");
    setVal("email", "email");
    setVal("number", "number");

    var gender = sessionStorage.getItem('gender');
    var maleEl = document.getElementById("gender-male");
    var femaleEl = document.getElementById("gender-female");
    if (gender === "M" && maleEl) maleEl.checked = true;
    if (gender === "F" && femaleEl) femaleEl.checked = true;

    var setChecked = function (id, key) {
        var el = document.getElementById(id);
        if (el) el.checked = sessionStorage.getItem(key) === "true";
    };

    setChecked("troubleshooting", "troubleshooting");
    setChecked("technicalproficiency", "technicalproficiency");
    setChecked("detailoriented", "detailoriented");
    setChecked("emotionalintelligence", "emotionalintelligence");
    setChecked("otherskill", "otherskill");
    setVal("otherskills", "otherskills");
}

function init() {
    loadInfo();
    setupLiveFormatting(); 
    startTimer();

    var formSections = document.querySelectorAll('[data-step]');
    var step1 = document.getElementById('step-1-indicator');
    var step2 = document.getElementById('step-2-indicator');
    var step3 = document.getElementById('step-3-indicator');

    function updateActiveStep(stepNumber) {
        [step1, step2, step3].forEach(function(el, idx) {
            if (el) {
                if (idx + 1 === stepNumber) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            }
        });
    }

    formSections.forEach(function(section) {
        section.addEventListener('focusin', function() {
            var stepNum = parseInt(section.getAttribute('data-step'), 10);
            if (stepNum) updateActiveStep(stepNum);
        });
    });

    var otherCheckbox = document.getElementById('otherskill');
    var otherTextareaWrap = document.getElementById('otherSkillsWrap');
    var otherTextarea = document.getElementById('otherskills');

    if (otherCheckbox && otherTextareaWrap) {
        otherCheckbox.addEventListener('change', function() {
            if (otherCheckbox.checked) {
                otherTextareaWrap.classList.add('active-highlight');
                if (otherTextarea) otherTextarea.focus();
            } else {
                otherTextareaWrap.classList.remove('active-highlight');
            }
        });
    }

    var timerWidget = document.getElementById('timer-enhancement');
    var timerDisplay = document.getElementById('timer-display');

    if (timerWidget && timerDisplay) {
        var observer = new MutationObserver(function() {
            var timeVal = parseInt(timerDisplay.textContent, 10);
            if (!isNaN(timeVal) && timeVal <= 15 && timeVal > 0) {
                timerWidget.classList.add('timer-warning');
            } else {
                timerWidget.classList.remove('timer-warning');
            }
        });

        observer.observe(timerDisplay, { childList: true, subtree: true, characterData: true });
    }

    setTimeout(function() {
        var toasts = document.querySelectorAll('.toast-notification');
        toasts.forEach(function(toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px) scale(0.95)';
            setTimeout(function() { toast.remove(); }, 300);
        });
    }, 8000);

    var refnum = document.getElementById("refnum");

    if (typeof (Storage) !== "undefined" && localStorage.getItem("refnum")) {
        if (refnum) refnum.value = localStorage.getItem("refnum");
    }

    var regForm = document.getElementById("regForm");
    if (regForm) {
        regForm.onsubmit = validate;

        regForm.addEventListener("change", saveInfo);
        regForm.addEventListener("input", saveInfo);

        regForm.addEventListener("reset", function () {
            var currentRef = refnum ? refnum.value : "";
            sessionStorage.clear();
            setTimeout(function () {
                if (refnum) refnum.value = currentRef;
            }, 0);
        });
    }

    var closeModalBtn = document.getElementById("closeModalBtn");
    var modalOkBtn = document.getElementById("modalOkBtn");
    var modalOverlay = document.getElementById("errorModal");

    if (closeModalBtn) closeModalBtn.onclick = hideErrorModal;
    if (modalOkBtn) modalOkBtn.onclick = hideErrorModal;

    if (modalOverlay) {
        modalOverlay.onclick = function (e) {
            if (e.target === modalOverlay) hideErrorModal();
        };
    }

    var closeTimerModalBtn = document.getElementById("closeTimerModalBtn");
    var restartTimerBtn = document.getElementById("restartTimerBtn");
    var timerModal = document.getElementById("timerModal");

    if (closeTimerModalBtn) closeTimerModalBtn.onclick = hideTimerModal;
    if (restartTimerBtn) {
        restartTimerBtn.onclick = function () {
            hideTimerModal();
            startTimer();
        };
    }

    if (timerModal) {
        timerModal.onclick = function (e) {
            if (e.target === timerModal) hideTimerModal();
        };
    }
}

window.onload = init;