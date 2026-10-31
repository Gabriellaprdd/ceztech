<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$page = "enhancementsPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="A showcase of the CSS and responsive design enhancements used throughout CezTech.">
    <link rel="stylesheet" href="css/enhancements.css">
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | CSS Enhancements</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>
        <main class="enhancement-page">
            <section class="page-hero page-hero--compact enhancement-hero" aria-labelledby="enhancement-title">
                <div class="page-hero__content enhancement-hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Design system · Part 1
                    </p>
                    <h1 id="enhancement-title">CSS enhancements triggered by URL navigation.</h1>
                    <p>
                        These interactive design features activate dynamically when you click any link below. 
                        Select an enhancement to see how it transforms the page layout and visual elements.
                    </p>
                </div>

                <aside class="enhancement-summary" aria-label="CSS enhancement summary">
                    <span class="enhancement-summary__type">CSS</span>
                    <strong class="counter" data-target="6">0</strong>
                    <p>Interactive hidden enhancements</p>
                    <div>
                        <span>Focus</span>
                        <b>Target Selectors</b>
                    </div>
                </aside>
            </section>

            <section class="enhancement-intro reveal-on-scroll" aria-labelledby="css-showcase-title">
                <div>
                    <p class="section-label">Enhancement library</p>
                    <h2 id="css-showcase-title">Click below to activate the hidden designs.</h2>
                </div>
                <p>
                    Each card below links to a specific feature on the Home or Careers page. Clicking a link will instantly activate the corresponding design effects.
                </p>
            </section>

            <section class="enhancement-grid">
                <article class="enhancement-card reveal-on-scroll delay-100 js-spotlight-card">
                    <span class="enhancement-card__number">01</span>
                    <div class="enhancement-card__tag">Home Page</div>
                    <h2>Animated Layered Background</h2>
                    <p>
                        Transforms the main hero section background into a smooth, animated multi-color gradient that continuously shifts across the screen.
                    </p>
                    <a href="index.php#animated-hero-bg">Activate Gradient BG <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-200 js-spotlight-card">
                    <span class="enhancement-card__number">02</span>
                    <div class="enhancement-card__tag">Home Page</div>
                    <h2>Levitation & Glass Shimmer</h2>
                    <p>
                        Adds a gentle floating motion, a soft glowing pastel halo, and a light reflection effect that sweeps across the feature image.
                    </p>
                    <a href="index.php#interactive-image-treatment">Activate Levitation & Shimmer <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-300 js-spotlight-card">
                    <span class="enhancement-card__number">03</span>
                    <div class="enhancement-card__tag">Home Page</div>
                    <h2>Staggered Neon Glow Reveal</h2>
                    <p>
                        Animates the value cards into view one by one with glowing highlighted borders and smooth entrance motion.
                    </p>
                    <a href="index.php#staggered-glow-cards">Activate Staggered Reveal <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-400 js-spotlight-card">
                    <span class="enhancement-card__number">04</span>
                    <div class="enhancement-card__tag">Careers Page</div>
                    <h2>Hero Stats Cascade</h2>
                    <p>
                        Gives the statistics summary box an active glow while smoothly sliding in the details sequentially.
                    </p>
                    <a href="jobs.php#jobs-hero-target">Activate Stats Cascade <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-500 js-spotlight-card">
                    <span class="enhancement-card__number">05</span>
                    <div class="enhancement-card__tag">Careers Page</div>
                    <h2>Interactive Role Focus</h2>
                    <p>
                        Highlights a specific job position card by scaling it up and adding a pulsing action button to guide attention.
                    </p>
                    <a href="jobs.php#designer-role-card">Activate Role Focus <span aria-hidden="true">→</span></a>
                </article>

                <article class="enhancement-card reveal-on-scroll delay-600 js-spotlight-card">
                    <span class="enhancement-card__number">06</span>
                    <div class="enhancement-card__tag">Careers Page</div>
                    <h2>Call-to-Action Shift</h2>
                    <p>
                        Turns the bottom call-to-action banner into a vibrant, animated pastel gradient background.
                    </p>
                    <a href="jobs.php#career-cta-target">Activate CTA Shift <span aria-hidden="true">→</span></a>
                </article>
            </section>

            <nav class="enhancement-switcher reveal-on-scroll" aria-label="Enhancement pages">
                <span class="active">01 · CSS</span>
                <a href="enhancements2.php">02 · JavaScript</a>
                <a href="enhancements3.php">03 · PHP</a>
            </nav>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>