<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$page = "phpenhancementsPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="PHP and database management enhancements used in the CezTech manager portal.">
    <link rel="stylesheet" href="css/enhancements.css">
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | PHP Enhancements</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="enhancement-page">
            <section class="page-hero page-hero--compact enhancement-hero enhancement-hero--php"
                     aria-labelledby="enhancement-title">
                <div class="page-hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" style="background-color: var(--blue-500, #4d9fe0);" aria-hidden="true"></span>
                        Server-side system · Part 3
                    </p>
                    <h1 id="enhancement-title">PHP enhancements that support manager access and applicant records.</h1>
                    <p>
                        These additions turn the project into a more complete web application
                        by introducing sessions, authenticated management and database-driven records.
                    </p>
                </div>

                <aside class="enhancement-summary" aria-label="PHP enhancement summary">
                    <span class="enhancement-summary__type enhancement-summary__type--blue">PHP</span>
                    <strong class="counter" data-target="2">0</strong>
                    <p>Documented server-side improvements</p>
                    <div>
                        <span>Focus</span>
                        <b>Management tools</b>
                    </div>
                </aside>
            </section>

            <section class="enhancement-intro reveal-on-scroll" aria-labelledby="php-showcase-title">
                <div>
                    <p class="section-label">Server-side features</p>
                    <h2 id="php-showcase-title">A controlled workflow for managing applications.</h2>
                </div>
                <p>
                    Managers can sign in, access protected tools and organise expression-of-interest
                    records through a database-backed interface.
                </p>
            </section>

            <section class="enhancement-grid enhancement-grid--two">
                <article class="enhancement-card enhancement-card--server reveal-on-scroll delay-100">
                    <span class="enhancement-card__number">01</span>
                    <div class="enhancement-card__tag">Authentication</div>
                    <h2>Manager login and logout</h2>
                    <p>
                        A dedicated login page creates a manager session after valid credentials
                        are supplied. The logout process clears that session and returns the manager
                        to the login screen.
                    </p>
                    <a href="login.php">Open manager login <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card enhancement-card--server reveal-on-scroll delay-200">
                    <span class="enhancement-card__number">02</span>
                    <div class="enhancement-card__tag">Database management</div>
                    <h2>Sortable EOI records</h2>
                    <p>
                        The management portal can display expression-of-interest records in a
                        selected order, helping managers review applicant data more efficiently.
                    </p>
                    <a href="manage.php#listform">Open management records <span aria-hidden="true">→</span></a>
                </article>
            </section>

            <section class="implementation-note implementation-note--server reveal-on-scroll delay-300">
                <div class="implementation-note__icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5"></path>
                        <path d="M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <div>
                    <p class="section-label">Application structure</p>
                    <h2>Shared PHP includes keep the interface consistent.</h2>
                    <p>
                        The header, navigation and footer are maintained as reusable include files,
                        while page variables control the active navigation state.
                    </p>
                </div>
            </section>

            <nav class="enhancement-switcher reveal-on-scroll delay-100" aria-label="Enhancement pages">
                <a href="enhancements.php">01 · CSS</a>
                <a href="enhancements2.php">02 · JavaScript</a>
                <span class="active">03 · PHP</span>
            </nav>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>