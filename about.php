<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
session_start();
}

$page = "aboutPage";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="Meet Gabriella Tio Pardede — student profile, hometown, personal interests and academic courses.">
    <link rel="stylesheet" href="css/about.css">
    <script src="scripts/about.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | About Me</title>
</head>

<body>
    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="about-page-main">
            <section class="about-hero" aria-labelledby="about-profile-name">
                <div class="about-hero__avatar">
                    <img src="images/photo.png" alt="Portrait of Gabriella Tio Pardede" width="160" height="190" decoding="async" loading="eager">
                    <span class="about-hero__badge">CS Student</span>
                </div>

                <div class="about-hero__details">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Student Profile
                    </p>
                    <h1 id="about-profile-name">Gabriella Tio Pardede</h1>
                    <p class="about-hero__bio">
                        I am a technology student with a focus on user-centred design, web application development, 
                        and creating practical digital solutions for real-world scenarios.
                    </p>

                    <div class="about-pills" aria-label="Student identity details">
                        <div class="about-pill">
                            <span class="about-pill__label">Birthplace</span>
                            <strong class="about-pill__val">Jakarta, Indonesia</strong>
                        </div>
                        <div class="about-pill">
                            <span class="about-pill__label">INTI Student ID</span>
                            <strong class="about-pill__val">J23040616</strong>
                        </div>
                        <div class="about-pill">
                            <span class="about-pill__label">Swinburne Student ID</span>
                            <strong class="about-pill__val">S104838959</strong>
                        </div>
                    </div>
                </div>
            </section>

            <div class="about-grid-two">
                <section class="section about-card" id="academic-overview" aria-labelledby="academic-title">
                    <div class="section-heading" style="margin-bottom: 16px;">
                        <p class="section-label">Academic Overview</p>
                        <h2 id="academic-title">Current Courses & Tutors</h2>
                    </div>

                    <div class="academic-cards">
                        <article class="academic-card">
                            <div class="academic-card__header">
                                <span>01</span>
                                <h3>Enrolled Units</h3>
                            </div>
                            <dl class="academic-list">
                                <div><dt>COS10003</dt><dd>Computer & Logic Essentials</dd></div>
                                <div><dt>COS10009</dt><dd>Intro to Programming</dd></div>
                                <div><dt>COS10011</dt><dd>Creating Web Applications</dd></div>
                                <div><dt>COS20001</dt><dd>User-Centred Design</dd></div>
                            </dl>
                        </article>

                        <article class="academic-card">
                            <div class="academic-card__header">
                                <span>02</span>
                                <h3>Teaching Team</h3>
                            </div>
                            <dl class="academic-list">
                                <div><dt>COS10003</dt><dd>Mr. Ang Chee Huei</dd></div>
                                <div><dt>COS10009</dt><dd>Ms. Siti Hawa Mohamed Said</dd></div>
                                <div><dt>COS10011</dt><dd>Ms. Pawani T. Rasaratnam</dd></div>
                                <div><dt>COS20001</dt><dd>Ms. Pawani T. Rasaratnam</dd></div>
                            </dl>
                        </article>
                    </div>
                </section>

                <article class="hometown-panel">
                    <div>
                        <p class="section-label">My Hometown</p>
                        <h2>Jakarta: Energetic, diverse & always moving.</h2>
                        <p style="margin-top: 12px;">
                            Jakarta, the capital of Indonesia, is known for its vibrant culture, 
                            rich history, and diverse population. Modern skyscrapers sit alongside 
                            traditional neighbourhoods, creating a city full of contrast and character.
                        </p>
                    </div>

                    <div class="hometown-tags" aria-label="Jakarta characteristics">
                        <span>Vibrant Culture</span>
                        <span>Rich History</span>
                        <span>Diverse Communities</span>
                        <span>Modern City Life</span>
                    </div>
                </article>
            </div>

            <section class="interest-grid" aria-label="Personal interests">
                <article class="interest-card">
                    <span class="interest-card__icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </span>
                    <p class="section-label">Reading List</p>
                    <h2>Favourite Books</h2>
                    <ul class="clean-list">
                        <li>
                            <strong>Pride and Prejudice</strong>
                            <span>Jane Austen</span>
                        </li>
                        <li>
                            <strong>The Great Gatsby</strong>
                            <span>F. Scott Fitzgerald</span>
                        </li>
                        <li>
                            <strong>Harry Potter</strong>
                            <span>J. K. Rowling</span>
                        </li>
                        <li>
                            <strong>To Kill a Mockingbird</strong>
                            <span>Harper Lee</span>
                        </li>
                    </ul>
                </article>

                <article class="interest-card">
                    <span class="interest-card__icon interest-card__icon--pink" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                            <line x1="7" y1="2" x2="7" y2="22"></line>
                            <line x1="17" y1="2" x2="17" y2="22"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="2" y1="7" x2="7" y2="7"></line>
                            <line x1="2" y1="17" x2="7" y2="17"></line>
                            <line x1="17" y1="17" x2="22" y2="17"></line>
                            <line x1="17" y1="7" x2="22" y2="7"></line>
                        </svg>
                    </span>
                    <p class="section-label">Watch List</p>
                    <h2>Favourite Films & Shows</h2>
                    <ul class="clean-list">
                        <li><strong>Family Feud</strong><span>Game Show</span></li>
                        <li><strong>The Lord of the Rings</strong><span>Fantasy</span></li>
                        <li><strong>Narnia</strong><span>Fantasy Adventure</span></li>
                        <li><strong>Criminal Minds</strong><span>Crime Drama</span></li>
                    </ul>
                </article>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>