<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION["login"])) {
    header("Location: manage.php");
    exit;
}

$page = "registerPage";
$error = "";

if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($username === "" || $password === "" || $confirm_password === "") {
        $error = "Please fill in all required fields.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $error = "Username must be 3–20 characters long (letters, numbers, underscores).";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^a-zA-Z0-9]/", $password)) {
        $error = "Password must contain at least one uppercase letter, one number, and one special character.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match. Please re-enter.";
    } else {
        include "config.php";
        $db = ceztech_db_connect();

        if (!$db) {
            $error = "The registration service is temporarily unavailable.";
        } else {
            mysqli_set_charset($db, "utf8mb4");

            $createTable = "CREATE TABLE IF NOT EXISTS manager (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            @mysqli_query($db, $createTable);

            $checkStmt = mysqli_prepare($db, "SELECT id FROM manager WHERE username = ? LIMIT 1");
            if ($checkStmt) {
                mysqli_stmt_bind_param($checkStmt, "s", $username);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);

                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    $error = "Username '" . htmlspecialchars($username) . "' is already taken. Please choose another.";
                    mysqli_stmt_close($checkStmt);
                } else {
                    mysqli_stmt_close($checkStmt);

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $insertStmt = mysqli_prepare($db, "INSERT INTO manager (username, password) VALUES (?, ?)");

                    if ($insertStmt) {
                        mysqli_stmt_bind_param($insertStmt, "ss", $username, $hashedPassword);
                        if (mysqli_stmt_execute($insertStmt)) {
                            mysqli_stmt_close($insertStmt);
                            mysqli_close($db);

                            header("Location: login.php?registered=1");
                            exit;
                        } else {
                            $error = "An error occurred while creating your account. Please try again.";
                            mysqli_stmt_close($insertStmt);
                        }
                    } else {
                        $error = "Failed to prepare registration request.";
                    }
                }
            } else {
                $error = "Unable to verify existing records.";
            }

            if ($db) mysqli_close($db);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="Register a new manager account for the CezTech portal.">
    <link rel="stylesheet" href="css/register.css">
    <script src="scripts/register.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>
    <title>CezTech | Manager Sign Up</title>
</head>

<body>
    <?php if ($error !== ""): ?>
        <div class="toast-notification-container" id="toastContainer">
            <div class="toast-notification toast-notification--error" role="alert">
                <div class="toast-notification__content">
                    <span class="toast-notification__icon" aria-hidden="true">!</span>
                    <div class="toast-notification__text-wrap">
                        <strong class="toast-notification__title">Registration Notice</strong>
                        <p class="toast-notification__text"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
                    </div>
                </div>
                <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="auth-main">
            <section class="auth-layout" aria-labelledby="register-title">
                <div class="auth-introduction">
                    <div class="auth-introduction__content">
                        <p class="eyebrow">
                            <span class="eyebrow__dot" aria-hidden="true"></span>
                            Manager Registration
                        </p>
                        <h1 id="register-title">Create a new manager account.</h1>
                        <p>
                            Register to gain administrative access to applicant expressions of interest,
                            manage recruitment workflows, and review candidate details.
                        </p>
                    </div>

                    <div class="auth-feature-list" aria-label="Manager registration features">
                        <div>
                            <span aria-hidden="true">01</span>
                            <strong>Secure Access</strong>
                            <p>Password protected manager portal.</p>
                        </div>
                        <div>
                            <span aria-hidden="true">02</span>
                            <strong>EOI Management</strong>
                            <p>Filter and organize candidate applications.</p>
                        </div>
                        <div>
                            <span aria-hidden="true">03</span>
                            <strong>Workflow Control</strong>
                            <p>Update candidate statuses seamlessly.</p>
                        </div>
                    </div>
                </div>

                <section class="login-card" aria-labelledby="register-form-title">
                    <div class="login-card__heading">
                        <div>
                            <p class="section-label">Manager portal</p>
                            <h2 id="register-form-title">Sign Up</h2>
                            <p>Fill out the details below to create your account.</p>
                        </div>
                    </div>

                    <form method="post" action="register.php" class="login-form">
                        <div class="field-group">
                            <label for="username">Username <span aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST["username"] ?? "", ENT_QUOTES, "UTF-8"); ?>"
                                autocomplete="username"
                                pattern="[a-zA-Z0-9_]{3,20}"
                                maxlength="20"
                                title="Username must be 3–20 characters long (letters, numbers, underscores only)"
                                placeholder="Create a username"
                                required
                            >
                        </div>

                        <div class="field-grid">
                            <div class="field-group">
                                <label for="password">Password <span aria-hidden="true">*</span></label>
                                <div class="password-wrap">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        autocomplete="new-password"
                                        placeholder="Create a password"
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

                            <div class="field-group">
                                <label for="confirm_password">Confirm password <span aria-hidden="true">*</span></label>
                                <div class="password-wrap">
                                    <input
                                        type="password"
                                        name="confirm_password"
                                        id="confirm_password"
                                        class="form-control"
                                        autocomplete="new-password"
                                        placeholder="Re-enter password"
                                        required
                                    >
                                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirm_password', this)" aria-label="Toggle password visibility">
                                        <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="button button--primary login-submit">
                            Create manager account
                            <span aria-hidden="true">→</span>
                        </button>
                    </form>

                    <p class="login-card__support">
                        Already have an account? <a href="login.php">Log in here</a>
                    </p>
                </section>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body> 
</html>