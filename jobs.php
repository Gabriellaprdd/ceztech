<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$page = "jobPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="Explore current technology career opportunities at CezTech."> 
    <link rel="stylesheet" href="css/jobs.css">
    <script src="scripts/jobs.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | Careers</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main>
            <section class="page-hero page-hero--jobs" id="jobs-hero-target" aria-labelledby="jobs-title">
                <div class="page-hero__content">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Careers at CezTech
                    </p>
                    <h1 id="jobs-title">Build useful technology with people who care.</h1>
                    <p>
                        Join a collaborative team where practical ideas, thoughtful design and reliable
                        technology come together to create meaningful digital experiences.
                    </p>

                    <div class="page-hero__actions">
                        <a class="button button--primary" href="#open-roles">View open roles</a>
                    </div>
                </div>

                <div class="career-summary" aria-label="Current recruitment summary">
                    <div class="career-summary__topline">
                        <span>Current opportunities</span>
                        <span class="availability-badge">Applications open</span>
                    </div>
                    <strong class="counter" data-target="2">0</strong>
                    <p>Full-time positions across support and product design.</p>
                    <div class="career-summary__stats">
                        <div>
                            <span>Location</span>
                            <strong>Newtown, NSW</strong>
                        </div>
                        <div>
                            <span>Work style</span>
                            <strong>Flexible</strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="jobs-intro reveal-on-scroll" aria-labelledby="open-roles">
                <div>
                    <p class="section-label">Open positions</p>
                    <h2 id="open-roles">Find the role that fits your strengths.</h2>
                </div>
                <p>
                    Each position includes a clear overview of the work, expected skills and benefits.
                    Select Apply to automatically carry the correct reference number into the form.
                </p>
            </section>

            <div class="role-list">
                <article class="role-card reveal-on-scroll delay-100" id="desktop-role-card" aria-labelledby="desktop-role-title">
                    <div class="role-card__media">
                        <img src="images/desktopsupport.png" alt="Desktop support specialist assisting with computer systems" loading="eager">
                        <span class="role-card__category">IT Operations</span>
                    </div>

                    <div class="role-card__main">
                        <div class="role-card__heading">
                            <div>
                                <p class="role-reference">Reference JS388</p>
                                <h2 id="desktop-role-title">Desktop Support Specialist</h2>
                            </div>
                            <span class="role-status">Full-time</span>
                        </div>

                        <p class="role-card__summary">
                            Keep CezTech teams productive by resolving technical issues, maintaining
                            workplace systems and supporting secure, reliable day-to-day operations.
                        </p>

                        <div class="role-facts" aria-label="Desktop Support Specialist role details">
                            <div>
                                <span>Salary</span>
                                <strong>$70,000–$104,000</strong>
                            </div>
                            <div>
                                <span>Reports to</span>
                                <strong>IT Manager</strong>
                            </div>
                            <div>
                                <span>Experience</span>
                                <strong>2+ years preferred</strong>
                            </div>
                        </div>

                        <div class="role-card__columns">
                            <section>
                                <h3>What you will do</h3>
                                <ul class="feature-list">
                                    <li>Provide hardware and software support to end-users.</li>
                                    <li>Document support work and recurring technical issues.</li>
                                    <li>Install, update and maintain workplace software.</li>
                                    <li>Support IT security, assets and inventory processes.</li>
                                </ul>
                            </section>

                            <section>
                                <h3>What you will bring</h3>
                                <ul class="feature-list feature-list--pink">
                                    <li>A qualification in IT or a related field.</li>
                                    <li>Confidence working with Windows and macOS.</li>
                                    <li>Strong troubleshooting and customer-service skills.</li>
                                    <li>CompTIA A+ or Microsoft certification is valued.</li>
                                </ul>
                            </section>
                        </div>
                    </div>

                    <aside class="role-card__aside" aria-label="Desktop Support Specialist benefits and application">
                        <div>
                            <p class="section-label">Included benefits</p>
                            <h3>Support for your best work.</h3>
                            <ul class="benefit-list">
                                <li>Healthcare coverage</li>
                                <li>Retirement savings plan</li>
                                <li>Professional development</li>
                                <li>Flexible work arrangements</li>
                            </ul>
                        </div>

                        <a href="apply.php" class="button button--primary role-apply" data-ref="JS388">
                            Apply for JS388
                            <span aria-hidden="true">→</span>
                        </a>
                    </aside>
                </article>

                <article class="role-card reveal-on-scroll delay-200" id="designer-role-card" aria-labelledby="designer-role-title">
                    <div class="role-card__media">
                        <img src="images/uxdesign.png" alt="UX and UI designer developing a digital interface" loading="lazy">
                        <span class="role-card__category">Product Design</span>
                    </div>

                    <div class="role-card__main">
                        <div class="role-card__heading">
                            <div>
                                <p class="role-reference">Reference JC257</p>
                                <h2 id="designer-role-title">UX/UI Designer</h2>
                            </div>
                            <span class="role-status">Full-time</span>
                        </div>

                        <p class="role-card__summary">
                            Shape intuitive product experiences by turning user needs and business
                            requirements into clear interfaces, prototypes and scalable design systems.
                        </p>

                        <div class="role-facts" aria-label="UX/UI Designer role details">
                            <div>
                                <span>Salary</span>
                                <strong>$77,386–$110,464</strong>
                            </div>
                            <div>
                                <span>Reports to</span>
                                <strong>Design Manager</strong>
                            </div>
                            <div>
                                <span>Experience</span>
                                <strong>3+ years preferred</strong>
                            </div>
                        </div>

                        <div class="role-card__columns">
                            <section>
                                <h3>What you will do</h3>
                                <ul class="feature-list">
                                    <li>Create wireframes, prototypes and polished mockups.</li>
                                    <li>Maintain consistency across product interfaces.</li>
                                    <li>Collaborate closely with developers and stakeholders.</li>
                                    <li>Translate project requirements into usable experiences.</li>
                                </ul>
                            </section>

                            <section>
                                <h3>What you will bring</h3>
                                <ul class="feature-list feature-list--pink">
                                    <li>A qualification in graphic or interaction design.</li>
                                    <li>Proficiency with modern interface-design software.</li>
                                    <li>Strong problem-solving and attention to detail.</li>
                                    <li>HTML, CSS and responsive-design knowledge is valued.</li>
                                </ul>
                            </section>
                        </div>
                    </div>

                    <aside class="role-card__aside" aria-label="UX/UI Designer benefits and application">
                        <div>
                            <p class="section-label">Included benefits</p>
                            <h3>Space to create and grow.</h3>
                            <ul class="benefit-list">
                                <li>Healthcare coverage</li>
                                <li>Retirement savings plan</li>
                                <li>Collaborative design culture</li>
                                <li>Flexible work arrangements</li>
                            </ul>
                        </div>

                        <a href="apply.php" class="button button--primary role-apply" data-ref="JC257">
                            Apply for JC257
                            <span aria-hidden="true">→</span>
                        </a>
                    </aside>
                </article>
            </div>

            <section class="career-cta reveal-on-scroll" id="career-cta-target" aria-labelledby="career-cta-title">
                <div>
                    <p class="section-label">Your next step</p>
                    <h2 id="career-cta-title">Ready to introduce yourself?</h2>
                    <p>Select an open role above to complete the application form and tell us which skills you would bring to CezTech.</p>
                </div>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>