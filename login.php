<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION["login"])) {
    header("Location: manage.php");
    exit;
}

$page = "loginPage";
$error = "";
$success = "";

if (isset($_GET["registered"]) && $_GET["registered"] === "1") {
    $success = "Account created successfully! Please log in below.";
}

if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Enter both your username and password.";
    } else {
        include "config.php";
        $db = ceztech_db_connect();

        if (!$db) {
            $error = "The manager portal is temporarily unavailable.";
        } else {
            mysqli_set_charset($db, "utf8mb4");

            $statement = mysqli_prepare(
                $db,
                "SELECT id, password FROM manager WHERE username = ? LIMIT 1"
            );

            if (!$statement) {
                $error = "The manager portal is temporarily unavailable.";
            } else {
                mysqli_stmt_bind_param($statement, "s", $username);
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $id, $dbPassword);

                if (mysqli_stmt_fetch($statement)) {
                    if (password_verify($password, $dbPassword) || $password === $dbPassword) {
                        mysqli_stmt_close($statement);
                        mysqli_close($db);

                        session_regenerate_id(true);
                        $_SESSION["login"] = $username;
                        header("Location: manage.php");
                        exit;
                    }
                }

                $error = "Invalid username or password.";
                mysqli_stmt_close($statement);
            }

            mysqli_close($db);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="Sign in to the restricted CezTech manager portal.">
    <link rel="stylesheet" href="css/login.css">
    <script src="scripts/login.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | Manager Log In</title>
</head>

<body>
    <?php if ($error !== "" || $success !== ""): ?>
        <div class="toast-notification-container" id="toastContainer">
            <?php if ($error !== ""): ?>
                <div class="toast-notification toast-notification--error" role="alert">
                    <div class="toast-notification__content">
                        <span class="toast-notification__icon" aria-hidden="true">!</span>
                        <div class="toast-notification__text-wrap">
                            <strong class="toast-notification__title">Authentication Notice</strong>
                            <p class="toast-notification__text"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
                        </div>
                    </div>
                    <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div class="toast-notification toast-notification--success" role="status">
                    <div class="toast-notification__content">
                        <span class="toast-notification__icon" aria-hidden="true">✓</span>
                        <div class="toast-notification__text-wrap">
                            <strong class="toast-notification__title">Success</strong>
                            <p class="toast-notification__text"><?php echo htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></p>
                        </div>
                    </div>
                    <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="auth-main">
            <section class="auth-layout" aria-labelledby="login-title">
                <div class="auth-introduction">
                    <div class="auth-introduction__content">
                        <p class="eyebrow">
                            <span class="eyebrow__dot" aria-hidden="true"></span>
                            Restricted workspace
                        </p>
                        <h1 id="login-title">Manager access to CezTech applications.</h1>
                        <p>
                            Sign in to review applicant records, organise expressions of interest
                            and manage the recruitment workflow.
                        </p>
                    </div>

                    <div class="auth-feature-list" aria-label="Manager portal features">
                        <div>
                            <span aria-hidden="true">01</span>
                            <strong>Applicant records</strong>
                            <p>Review submitted expressions of interest.</p>
                        </div>
                        <div>
                            <span aria-hidden="true">02</span>
                            <strong>Record controls</strong>
                            <p>Sort and manage recruitment information.</p>
                        </div>
                        <div>
                            <span aria-hidden="true">03</span>
                            <strong>Session access</strong>
                            <p>Keep management tools secure and isolated.</p>
                        </div>
                    </div>
                </div>

                <section class="login-card" aria-labelledby="login-form-title">
                    <div class="login-card__heading">
                        <div>
                            <p class="section-label">Manager portal</p>
                            <h2 id="login-form-title">Welcome back</h2>
                            <p>Enter your manager credentials to continue.</p>
                        </div>
                    </div>

                    <form method="post" action="login.php" class="login-form" autocomplete="off">
                        <div class="field-group">
                            <label for="username">Username <span aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST["username"] ?? "", ENT_QUOTES, "UTF-8"); ?>"
                                autocomplete="off"
                                pattern="[a-zA-Z0-9_]{3,20}"
                                maxlength="20"
                                title="Username must be 3–20 characters long (letters, numbers, underscores only)"
                                placeholder="Enter your username"
                                required
                            >
                        </div>

                        <div class="field-group">
                            <label for="password">Password <span aria-hidden="true">*</span></label>
                            <div class="password-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    autocomplete="off"
                                    placeholder="Enter your password"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle password visibility">
                                    <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="button button--primary login-submit">
                            Log in to manager portal
                            <span aria-hidden="true">→</span>
                        </button>
                    </form>

                    <p class="login-card__support">
                        Need an account? <a href="register.php">Sign up here</a>
                    </p>
                </section>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>