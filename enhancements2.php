<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$page = "enhancement2Page";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="JavaScript and jQuery enhancements implemented in the CezTech website.">
    <link rel="stylesheet" href="css/enhancements.css">
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | JavaScript Enhancements</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="enhancement-page">
            <section class="page-hero page-hero--compact enhancement-hero enhancement-hero--javascript"
                     aria-labelledby="enhancement-title">
                <div class="page-hero__content enhancement-hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Interaction system · Part 2
                    </p>
                    <h1 id="enhancement-title">JavaScript enhancements that make the interface responsive.</h1>
                    <p>
                        These interactive features add real-time countdown timers, smart form auto-fill, 
                        saved responses, and customized popups for a smooth user experience.
                    </p>
                </div>

                <aside class="enhancement-summary" aria-label="JavaScript enhancement summary">
                    <span class="enhancement-summary__type enhancement-summary__type--pink">JavaScript</span>
                    <strong class="counter" data-target="4">0</strong>
                    <p>Interactive DOM and Logic features</p>
                    <div>
                        <span>Focus</span>
                        <b>User interaction</b>
                    </div>
                </aside>
            </section>

            <section class="enhancement-intro reveal-on-scroll" aria-labelledby="javascript-showcase-title">
                <div>
                    <p class="section-label">Interaction features</p>
                    <h2 id="javascript-showcase-title">Click below to highlight the JS features.</h2>
                </div>
                <p>
                    Click any card below to navigate to the Application page and see the interactive JavaScript features in action.
                </p>
            </section>

            <section class="enhancement-grid enhancement-grid--two">
                <article class="enhancement-card reveal-on-scroll delay-100 js-spotlight-card">
                    <span class="enhancement-card__number">01</span>
                    <div class="enhancement-card__tag">Timing Events</div>
                    <h2>Live Timer & Custom Modal</h2>
                    <p>
                        Displays a live on-screen countdown timer and a custom popup warning that safely disables the form when the time limit ends.
                    </p>
                    <a href="apply.php#timer-enhancement">View Live Timer <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-200 js-spotlight-card">
                    <span class="enhancement-card__number">02</span>
                    <div class="enhancement-card__tag">Data Persistence</div>
                    <h2>Session & Local Storage</h2>
                    <p>
                        Automatically transfers your chosen job position from the Careers page and saves your progress so form details are preserved if you reload.
                    </p>
                    <a href="apply.php#storage-enhancement">View Auto-fill Reference <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-300 js-spotlight-card">
                    <span class="enhancement-card__number">03</span>
                    <div class="enhancement-card__tag">DOM Injection</div>
                    <h2>Dynamic Page Instructions</h2>
                    <p>
                        Dynamically loads tailored guidance text into the application page after the content finishes loading.
                    </p>
                    <a href="apply.php#intro-enhancement">View Injected Text <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-400 js-spotlight-card">
                    <span class="enhancement-card__number">04</span>
                    <div class="enhancement-card__tag">Form Validation</div>
                    <h2>Custom Error Modal</h2>
                    <p>
                        Validates all form fields upon submission and presents a clear list of any missing or incorrect details in a modern popup notification.
                    </p>
                    <a href="apply.php#validation-enhancement">View Submit Actions <span aria-hidden="true">→</span></a>
                </article>
            </section>

            <nav class="enhancement-switcher reveal-on-scroll" aria-label="Enhancement pages">
                <a href="enhancements.php">01 · CSS</a>
                <span class="active">02 · JavaScript</span>
                <a href="enhancements3.php">03 · PHP</a>
            </nav>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>