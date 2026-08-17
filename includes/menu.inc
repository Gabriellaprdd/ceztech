<?php
$enhancementPages = ["enhancementsPage", "enhancement2Page", "phpenhancementsPage"];
$isEnhancementPage = in_array($page ?? "", $enhancementPages, true);
?>
<header class="site-header" id="adaptive-nav">
    <nav class="navbar" aria-label="Primary navigation">
        <a class="brand" href="index.php" aria-label="CezTech home">
            <span class="brand__mark">
                <img src="images/logo.png" alt="">
            </span>
            <span class="brand__text">
                <strong>CezTech</strong>
                <small>Technology with purpose</small>
            </span>
        </a>

        <div class="nav-links">
            <a href="index.php"
               class="<?php echo (($page ?? '') === 'indexPage') ? 'active' : ''; ?>"
               <?php echo (($page ?? '') === 'indexPage') ? 'aria-current="page"' : ''; ?>>Home</a>

            <a href="jobs.php"
               class="<?php echo (($page ?? '') === 'jobPage') ? 'active' : ''; ?>"
               <?php echo (($page ?? '') === 'jobPage') ? 'aria-current="page"' : ''; ?>>Jobs</a>

            <a href="apply.php"
               class="<?php echo (($page ?? '') === 'applyPage') ? 'active' : ''; ?>"
               <?php echo (($page ?? '') === 'applyPage') ? 'aria-current="page"' : ''; ?>>Apply</a>

            <a href="about.php"
               class="<?php echo (($page ?? '') === 'aboutPage') ? 'active' : ''; ?>"
               <?php echo (($page ?? '') === 'aboutPage') ? 'aria-current="page"' : ''; ?>>About</a>

            <details class="nav-dropdown">
                <summary class="<?php echo $isEnhancementPage ? 'active' : ''; ?>">Enhancements</summary>
                <div class="nav-dropdown__menu">
                    <a href="enhancements.php" class="<?php echo (($page ?? '') === 'enhancementsPage') ? 'active' : ''; ?>">CSS</a>
                    <a href="enhancements2.php" class="<?php echo (($page ?? '') === 'enhancement2Page') ? 'active' : ''; ?>">JavaScript</a>
                    <a href="enhancements3.php" class="<?php echo (($page ?? '') === 'phpenhancementsPage') ? 'active' : ''; ?>">PHP</a>
                </div>
            </details>

            <?php if (isset($_SESSION['login'])): ?>
                <a href="manage.php"
                   class="<?php echo (($page ?? '') === 'managePage') ? 'active' : ''; ?>"
                   <?php echo (($page ?? '') === 'managePage') ? 'aria-current="page"' : ''; ?>>Manage</a>
            <?php endif; ?>

            <a href="login.php"
               class="nav-login <?php echo (($page ?? '') === 'loginPage') ? 'active' : ''; ?>"
               <?php echo (($page ?? '') === 'loginPage') ? 'aria-current="page"' : ''; ?>>Log in</a>
        </div>
    </nav>
</header>