<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$page = "applyPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="Submit an application for a current CezTech technology role.">
    <link rel="stylesheet" href="css/apply.css">
    <script src="scripts/apply.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | Apply Now</title>
</head>

<body>
    <?php if (!empty($_SESSION["toast_success"]) || !empty($_SESSION["toast_error"])): ?>
        <div class="toast-notification-container" id="toastContainer">
            <?php if (!empty($_SESSION["toast_error"])): ?>
                <div class="toast-notification toast-notification--error" role="alert">
                    <div class="toast-notification__content">
                        <span class="toast-notification__icon" aria-hidden="true">!</span>
                        <div class="toast-notification__text-wrap">
                            <strong class="toast-notification__title">Application Notice</strong>
                            <p class="toast-notification__text"><?php echo htmlspecialchars($_SESSION["toast_error"], ENT_QUOTES, "UTF-8"); ?></p>
                        </div>
                    </div>
                    <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
                </div>
                <?php unset($_SESSION["toast_error"]); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION["toast_success"])): ?>
                <div class="toast-notification toast-notification--success" role="status">
                    <div class="toast-notification__content">
                        <span class="toast-notification__icon" aria-hidden="true">✓</span>
                        <div class="toast-notification__text-wrap">
                            <strong class="toast-notification__title">Success</strong>
                            <p class="toast-notification__text"><?php echo htmlspecialchars($_SESSION["toast_success"], ENT_QUOTES, "UTF-8"); ?></p>
                        </div>
                    </div>
                    <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
                </div>
                <?php unset($_SESSION["toast_success"]); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div id="timer-enhancement" class="floating-timer">
        <span class="floating-timer__icon">⏱</span>
        <div>
            <strong>Time Remaining</strong>
            <span id="timer-display">60</span> seconds
        </div>
    </div>

    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main>
            <section class="page-hero page-hero--application" id="intro-enhancement" aria-labelledby="application-title">
                <div class="page-hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        CezTech careers
                    </p>
                    <h1 id="application-title">Take the next step in your technology career.</h1>
                    <p id="intro-text-target">
                        Tell us about your background, strengths and the role that interests you.
                        Your information will be submitted securely to the CezTech recruitment team.
                    </p>
                </div>

                <ol class="application-steps" id="step-tracker" aria-label="Application process steps">
                    <li class="active" id="step-1-indicator">
                        <span>01</span>
                        <div>
                            <strong>Your details</strong>
                            <small>Contact and personal information</small>
                        </div>
                    </li>
                    <li id="step-2-indicator">
                        <span>02</span>
                        <div>
                            <strong>Your skills</strong>
                            <small>Experience relevant to the role</small>
                        </div>
                    </li>
                    <li id="step-3-indicator">
                        <span>03</span>
                        <div>
                            <strong>Submit</strong>
                            <small>Review and send your application</small>
                        </div>
                    </li>
                </ol>
            </section>

            <div class="application-layout">
                <aside class="application-guide" aria-labelledby="application-guide-title">
                    <div class="application-guide__sticky">
                        <p class="section-label">Before you apply</p>
                        <h2 id="application-guide-title">A few useful reminders.</h2>
                        <p>
                            Complete each required field carefully. Your selected job reference should
                            appear automatically after you choose Apply on the Careers page.
                        </p>

                        <div class="guide-list">
                            <div>
                                <span aria-hidden="true">✓</span>
                                <p><strong>Use accurate details</strong><br>Enter information that the recruitment team can verify.</p>
                            </div>
                            <div>
                                <span aria-hidden="true">✓</span>
                                <p><strong>Check the reference</strong><br>JS388 is Desktop Support and JC257 is UX/UI Design.</p>
                            </div>
                            <div>
                                <span aria-hidden="true">✓</span>
                                <p><strong>Describe other skills</strong><br>Add a short explanation when selecting Other.</p>
                            </div>
                        </div>

                        <a class="application-guide__link" href="jobs.php">
                            Review available positions <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </aside>

                <section class="application-panel" aria-labelledby="form-title">
                    <div class="application-panel__heading">
                        <div>
                            <p class="section-label">Application form</p>
                            <h2 id="form-title">Candidate information</h2>
                        </div>
                        <span class="required-note"><span aria-hidden="true">*</span> Required fields</span>
                    </div>

                    <form id="regForm" method="post" action="processEOI.php" novalidate>
                        <fieldset class="form-section" data-step="1">
                            <legend>
                                <span>01</span>
                                Role selection
                            </legend>

                            <div class="field-grid field-grid--single">
                                <div class="field-group" id="storage-enhancement">
                                    <label for="refnum">Job reference number <span aria-hidden="true">*</span></label>
                                    <input type="text" name="refnum" id="refnum" class="form-control"
                                           pattern="[a-zA-Z0-9]{5}" maxlength="5" placeholder="Select a role first"
                                           aria-describedby="refnum-help" readonly required>
                                    <small id="refnum-help">This is filled from the position selected on the Careers page.</small>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-section" data-step="1">
                            <legend>
                                <span>02</span>
                                Personal details
                            </legend>

                            <div class="field-grid">
                                <div class="field-group">
                                    <label for="firstname">First name <span aria-hidden="true">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control"
                                           maxlength="20" pattern="[A-Za-z]+" title="Only letters are allowed" placeholder="Enter first name"
                                           autocomplete="given-name" required>
                                </div>

                                <div class="field-group">
                                    <label for="lastname">Last name <span aria-hidden="true">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control"
                                           maxlength="20" pattern="[A-Za-z]+" title="Only letters are allowed (no spaces)" placeholder="Enter last name"
                                           autocomplete="family-name" required>
                                </div>

                                <div class="field-group">
                                    <label for="dob">Date of birth <span aria-hidden="true">*</span></label>
                                    <input type="date" name="dob" id="dob" class="form-control"
                                           autocomplete="bday" required>
                                </div>

                                <div class="field-group">
                                    <span class="field-label">Gender <span aria-hidden="true">*</span></span>
                                    <div class="choice-row" role="radiogroup" aria-label="Gender">
                                        <label class="choice-chip" for="gender-male">
                                            <input type="radio" id="gender-male" name="gender" value="M" required>
                                            <span>Male</span>
                                        </label>
                                        <label class="choice-chip" for="gender-female">
                                            <input type="radio" id="gender-female" name="gender" value="F">
                                            <span>Female</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-section" data-step="1">
                            <legend>
                                <span>03</span>
                                Address and contact
                            </legend>

                            <div class="field-grid">
                                <div class="field-group field-group--wide">
                                    <label for="address">Street address <span aria-hidden="true">*</span></label>
                                    <input type="text" name="address" id="address" class="form-control"
                                           maxlength="40" pattern="[A-Za-z0-9\s.,\/-]+" title="Only letters, numbers, spaces, and .,/- are allowed" placeholder="Enter street address"
                                           autocomplete="street-address" required>
                                </div>

                                <div class="field-group">
                                    <label for="suburb">Suburb or town <span aria-hidden="true">*</span></label>
                                    <input type="text" name="suburb" id="suburb" class="form-control"
                                           maxlength="40" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" placeholder="Enter suburb or town"
                                           autocomplete="address-level2" required>
                                </div>

                                <div class="field-group">
                                    <label for="state">State <span aria-hidden="true">*</span></label>
                                    <select name="state" id="state" class="form-control" autocomplete="address-level1" required>
                                        <option value="">Select state</option>
                                        <option value="VIC">VIC</option>
                                        <option value="NSW">NSW</option>
                                        <option value="QLD">QLD</option>
                                        <option value="NT">NT</option>
                                        <option value="WA">WA</option>
                                        <option value="SA">SA</option>
                                        <option value="TAS">TAS</option>
                                        <option value="ACT">ACT</option>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label for="postcode">Postcode <span aria-hidden="true">*</span></label>
                                    <input type="text" name="postcode" id="postcode" class="form-control"
                                           pattern="[0-9]{4}" title="Must be exactly 4 digits" placeholder="Enter postcode" maxlength="4" inputmode="numeric"
                                           autocomplete="postal-code" required>
                                </div>

                                <div class="field-group">
                                    <label for="number">Phone number <span aria-hidden="true">*</span></label>
                                    <input type="tel" name="number" id="number" class="form-control"
                                           pattern="[0-9]{8,12}" title="Must be between 8 and 12 digits" placeholder="Enter phone number" maxlength="12" inputmode="numeric"
                                           autocomplete="tel" required>
                                </div>

                                <div class="field-group field-group--wide">
                                    <label for="email">Email address <span aria-hidden="true">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                           pattern="[A-Za-z0-9@.]+" title="Only letters, numbers, @, and . are allowed" placeholder="Enter email address"
                                           autocomplete="email" required>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-section" data-step="2">
                            <legend>
                                <span>04</span>
                                Skills and experience
                            </legend>

                            <p class="field-intro">Select every skill that applies to you.</p>
                            <div class="skill-grid">
                                <label class="skill-option" for="troubleshooting">
                                    <input type="checkbox" name="skill[]" id="troubleshooting" value="troubleshooting">
                                    <span class="skill-option__check" aria-hidden="true">✓</span>
                                    <span>
                                        <strong>Troubleshooting</strong>
                                        <small>Diagnosing technical or process issues</small>
                                    </span>
                                </label>

                                <label class="skill-option" for="technicalproficiency">
                                    <input type="checkbox" name="skill[]" id="technicalproficiency" value="technical proficiency">
                                    <span class="skill-option__check" aria-hidden="true">✓</span>
                                    <span>
                                        <strong>Technical proficiency</strong>
                                        <small>Using digital tools confidently</small>
                                    </span>
                                </label>

                                <label class="skill-option" for="detailoriented">
                                    <input type="checkbox" name="skill[]" id="detailoriented" value="detail oriented">
                                    <span class="skill-option__check" aria-hidden="true">✓</span>
                                    <span>
                                        <strong>Detail-oriented</strong>
                                        <small>Producing accurate, consistent work</small>
                                    </span>
                                </label>

                                <label class="skill-option" for="emotionalintelligence">
                                    <input type="checkbox" name="skill[]" id="emotionalintelligence" value="emotional intelligence">
                                    <span class="skill-option__check" aria-hidden="true">✓</span>
                                    <span>
                                        <strong>Emotional intelligence</strong>
                                        <small>Communicating with empathy and awareness</small>
                                    </span>
                                </label>

                                <label class="skill-option skill-option--wide" for="otherskill">
                                    <input type="checkbox" name="skill[]" id="otherskill" value="other skills">
                                    <span class="skill-option__check" aria-hidden="true">✓</span>
                                    <span>
                                        <strong>Other relevant skills</strong>
                                        <small>Select this option and explain below</small>
                                    </span>
                                </label>
                            </div>

                            <div class="field-group field-group--textarea" id="otherSkillsWrap">
                                <label for="otherskills">Other skills</label>
                                <textarea rows="5" name="otherskills" id="otherskills" class="form-control"
                                          placeholder="Briefly describe any other relevant skills or experience."></textarea>
                                <small>Optional unless Other relevant skills is selected.</small>
                            </div>
                        </fieldset>

                        <div class="form-actions" id="validation-enhancement" data-step="3">
                            <p>Review your details before submitting the application.</p>
                            <div>
                                <button type="reset" class="button button--secondary">Reset form</button>
                                <button type="submit" id="submitBtn" class="button button--primary">Submit application</button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>

    <div id="errorModal" class="modal-overlay" aria-hidden="true" role="dialog" aria-labelledby="modalTitle">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span class="modal-icon" aria-hidden="true">!</span>
                    <div>
                        <h3 id="modalTitle">Form Validation Notice</h3>
                        <p class="modal-subtitle">Please check the required fields below.</p>
                    </div>
                </div>
                <button type="button" id="closeModalBtn" class="modal-close" aria-label="Close dialog">&times;</button>
            </div>
            <div class="modal-body">
                <ul id="modalErrorList" class="modal-error-list"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalOkBtn" class="button button--primary">Review details</button>
            </div>
        </div>
    </div>

    <div id="timerModal" class="modal-overlay" aria-hidden="true" role="dialog">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <span class="modal-icon" style="background: linear-gradient(135deg, var(--blue-700), var(--blue-600));" aria-hidden="true">⏱</span>
                    <div>
                        <h3 style="color: var(--blue-900);">Time Expired</h3>
                        <p class="modal-subtitle">Your 60-second window has closed.</p>
                    </div>
                </div>
                <button type="button" id="closeTimerModalBtn" class="modal-close" aria-label="Close modal">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 11px; color: var(--muted); padding: 10px 0; line-height: 1.6;">
                    For security reasons, this application form has timed out. Your submit button has been disabled. 
                    Please restart the timer to continue filling out your application.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="restartTimerBtn" class="button button--primary">Restart Timer</button>
            </div>
        </div>
    </div>
</body>
</html>