<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$page = "indexPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <link rel="stylesheet" href="css/index.css">
    <script src="scripts/index.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | Technology, Careers and Innovation</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main>
            <section class="hero reveal-on-scroll" id="animated-hero-bg" aria-labelledby="hero-title">
                <div class="hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Future-focused technology team
                    </p>

                    <h1 id="hero-title">
                        Technology that moves <span class="cycling-word" id="hero-word-cycler">people</span> forward.
                    </h1>

                    <p class="hero__description">
                        CezTech brings curious thinkers, practical problem-solvers and digital specialists
                        together to create useful technology with lasting impact.
                    </p>

                    <div class="hero__actions">
                        <a class="button button--primary" href="jobs.php">Explore careers</a>
                        <a class="button button--secondary" href="#contact">Contact our team</a>
                    </div>

                    <div class="hero__metrics" aria-label="CezTech highlights">
                        <div>
                            <strong class="counter" data-target="4">0</strong>
                            <span>Core values</span>
                        </div>
                        <div>
                            <strong class="counter" data-target="2">0</strong>
                            <span>Open roles</span>
                        </div>
                        <div>
                            <strong class="counter" data-target="1">0</strong>
                            <span>Shared mission</span>
                        </div>
                    </div>
                </div>

                <div class="hero__visual" id="hero-3d-wrap">
                    <div class="hero__image-wrap">
                        <img src="images/left.png" alt="CezTech team collaborating on digital solutions" loading="eager">
                    </div>

                    <div class="floating-card floating-card--top">
                        <span class="floating-card__icon" aria-hidden="true">✦</span>
                        <div>
                            <strong>Ideas into action</strong>
                            <span>Designed for real-world use</span>
                        </div>
                    </div>

                    <div class="floating-card floating-card--bottom">
                        <span class="status-dot" aria-hidden="true"></span>
                        <div>
                            <strong>Now hiring</strong>
                            <span>Build what comes next</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section about-preview reveal-on-scroll" aria-labelledby="about-title">
                <div class="section-heading">
                    <p class="section-label">About CezTech</p>
                    <h2 id="about-title">Innovation should feel useful, human and achievable.</h2>
                    <p>
                        We combine technology, design and teamwork to help businesses and individuals
                        thrive in a changing digital environment.
                    </p>
                </div>

                <div class="about-preview__layout">
                    <div class="about-preview__image" id="interactive-image-treatment">
                        <img src="images/right.png" alt="Technology professionals working together at CezTech" loading="lazy">
                    </div>

                    <div class="about-preview__content">
                        <p class="about-preview__lead">
                            We are a group of visionaries, problem-solvers and pioneers who believe that
                            strong digital products begin with a clear purpose.
                        </p>

                        <div class="check-list" aria-label="How CezTech works">
                            <div class="reveal-on-scroll delay-100">
                                <span aria-hidden="true">01</span>
                                <p><strong>Understand the challenge</strong> before selecting the technology.</p>
                            </div>
                            <div class="reveal-on-scroll delay-200">
                                <span aria-hidden="true">02</span>
                                <p><strong>Work across disciplines</strong> to create better outcomes.</p>
                            </div>
                            <div class="reveal-on-scroll delay-300">
                                <span aria-hidden="true">03</span>
                                <p><strong>Build for lasting value</strong>, not short-term novelty.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section values reveal-on-scroll" aria-labelledby="welcome">
                <div class="section-heading section-heading--split">
                    <div>
                        <p class="section-label">Why choose us</p>
                        <h2 id="welcome">A workplace built around meaningful progress.</h2>
                    </div>
                    <p>
                        Our culture gives people space to contribute, develop their skills and see the
                        practical impact of their work.
                    </p>
                </div>

                <div class="value-grid" id="staggered-glow-cards">
                    <article class="value-card reveal-on-scroll delay-100">
                        <span class="value-card__number">01</span>
                        <div class="value-card__icon" aria-hidden="true">◇</div>
                        <h3>Innovation</h3>
                        <p>Work on thoughtful projects that challenge assumptions and shape better digital experiences.</p>
                    </article>

                    <article class="value-card reveal-on-scroll delay-200">
                        <span class="value-card__number">02</span>
                        <div class="value-card__icon" aria-hidden="true">◎</div>
                        <h3>Collaboration</h3>
                        <p>Share different perspectives in a team where good ideas matter more than job titles.</p>
                    </article>

                    <article class="value-card reveal-on-scroll delay-300">
                        <span class="value-card__number">03</span>
                        <div class="value-card__icon" aria-hidden="true">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </div>
                        <h3>Growth</h3>
                        <p>Develop through practical challenges, supportive feedback and access to modern resources.</p>
                    </article>

                    <article class="value-card reveal-on-scroll delay-400">
                        <span class="value-card__number">04</span>
                        <div class="value-card__icon" aria-hidden="true">✦</div>
                        <h3>Impact</h3>
                        <p>Help improve systems, services and everyday experiences through technology that solves real needs.</p>
                    </article>
                </div>
            </section>

            <section class="contact-section reveal-on-scroll" id="contact" aria-labelledby="contact-title">
                <div class="contact-section__intro">
                    <p class="section-label">Contact us</p>
                    <h2 id="contact-title">Start a conversation with CezTech.</h2>
                    <p>
                        Have a question about our work or current opportunities? Reach our team using
                        the details below.
                    </p>
                </div>

                <div class="contact-grid">
                    <article class="contact-card reveal-on-scroll delay-100">
                        <img src="images/address.png" alt="" aria-hidden="true">
                        <div>
                            <p class="contact-card__label">Office</p>
                            <address>6/22 Adelaide St,<br>Newtown NSW 2042,<br>Australia</address>
                        </div>
                    </article>

                    <article class="contact-card reveal-on-scroll delay-200">
                        <img src="images/phone.png" alt="" aria-hidden="true">
                        <div>
                            <p class="contact-card__label">Phone</p>
                            <a href="tel:+61212647780">+61 2 1264 7780</a>
                            <span>Monday–Friday, 9:00–17:00</span>
                        </div>
                    </article>

                    <article class="contact-card reveal-on-scroll delay-300">
                        <img src="images/mail.png" alt="" aria-hidden="true">
                        <div>
                            <p class="contact-card__label">Email</p>
                            <a href="mailto:info@ceztech.com">info@ceztech.com</a>
                            <span>We usually reply within two business days.</span>
                        </div>
                    </article>
                </div>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>