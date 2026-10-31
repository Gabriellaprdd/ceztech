<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$page = "managePage";

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim((string)$data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    return $data;
}

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function bind_statement_values($statement, $types, &$params) {
    if ($types === "") {
        return true;
    }

    $bindValues = [];
    $bindValues[] = &$types;

    foreach ($params as $key => $value) {
        $bindValues[] = &$params[$key];
    }

    return call_user_func_array([$statement, "bind_param"], $bindValues);
}

function select_rows($connection, $sql, $types = "", $params = []) {
    $statement = mysqli_prepare($connection, $sql);

    if (!$statement) {
        return [[], "The requested records could not be prepared."];
    }

    if (!bind_statement_values($statement, $types, $params)) {
        mysqli_stmt_close($statement);
        return [[], "The requested filters could not be applied."];
    }

    if (!mysqli_stmt_execute($statement)) {
        mysqli_stmt_close($statement);
        return [[], "The requested records could not be loaded."];
    }

    $result = mysqli_stmt_get_result($statement);

    if ($result === false) {
        mysqli_stmt_close($statement);
        return [[], "The requested records could not be read."];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_free_result($result);
    mysqli_stmt_close($statement);

    return [$rows, ""];
}

function write_record($connection, $sql, $types, $params) {
    $statement = mysqli_prepare($connection, $sql);

    if (!$statement) {
        return [0, "The requested change could not be prepared."];
    }

    if (!bind_statement_values($statement, $types, $params)) {
        mysqli_stmt_close($statement);
        return [0, "The requested values could not be applied."];
    }

    if (!mysqli_stmt_execute($statement)) {
        mysqli_stmt_close($statement);
        return [0, "The requested database change could not be completed."];
    }

    $affectedRows = mysqli_stmt_affected_rows($statement);
    mysqli_stmt_close($statement);

    return [$affectedRows, ""];
}

function load_dashboard_counts($connection) {
    $counts = [
        "total" => 0,
        "new_count" => 0,
        "current_count" => 0,
        "final_count" => 0
    ];

    $sql = "
        SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN status = 'New' THEN 1 ELSE 0 END) AS new_count,
            SUM(CASE WHEN status = 'Current' THEN 1 ELSE 0 END) AS current_count,
            SUM(CASE WHEN status = 'Final' THEN 1 ELSE 0 END) AS final_count
        FROM eoi
    ";

    $result = mysqli_query($connection, $sql);

    if ($result && ($row = mysqli_fetch_assoc($result))) {
        foreach ($counts as $key => $value) {
            $counts[$key] = (int) ($row[$key] ?? 0);
        }
        mysqli_free_result($result);
    }

    return $counts;
}

function applicant_skills($row) {
    $skills = [];

    foreach (["skill1", "skill2", "skill3", "skill4", "skill5"] as $column) {
        $value = trim((string) ($row[$column] ?? ""));

        if ($value !== "" && !in_array($value, $skills, true)) {
            $skills[] = $value;
        }
    }

    $otherSkills = trim((string) ($row["otherskills"] ?? ""));

    if ($otherSkills !== "") {
        $skills[] = $otherSkills;
    }

    return $skills;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION["csrf_token"];
$message = "";
$messageType = "info";
$resultRows = [];
$resultTitle = "Recent applications";
$resultDescription = "The ten most recently submitted expressions of interest.";
$databaseAvailable = false;

$searchRefnum = sanitize_input($_POST["refnum"] ?? "");
$selectedSort = sanitize_input($_POST["sort"] ?? "");
$listRefnum = sanitize_input($_POST["list_refnum"] ?? "");

$listFirstname = preg_replace("/[^A-Za-z]/", "", sanitize_input($_POST["firstname"] ?? ""));
$listLastname = preg_replace("/[^A-Za-z]/", "", sanitize_input($_POST["lastname"] ?? ""));

$statusEoiNumber = sanitize_input($_POST["eoinumber"] ?? "");
$selectedStatus = sanitize_input($_POST["status"] ?? "");
$deleteRefnum = sanitize_input($_POST["delete_refnum"] ?? "");

$dashboardCounts = [
    "total" => 0,
    "new_count" => 0,
    "current_count" => 0,
    "final_count" => 0
];

require_once "config.php";

$connection = ceztech_db_connect();

if (!$connection) {
    $message = "The manager dashboard cannot connect to the database.";
    $messageType = "error";
} else {
    $databaseAvailable = true;
    mysqli_set_charset($connection, "utf8mb4");

    $allowedSortColumns = [
        "EOInumber" => "EOInumber",
        "firstname" => "firstname",
        "lastname" => "lastname",
        "address" => "address",
        "suburb" => "suburb",
        "state" => "state",
        "postcode" => "postcode",
        "email" => "email",
        "number" => "number",
        "status" => "status"
    ];

    $sortColumn = $allowedSortColumns[$selectedSort] ?? "EOInumber";

    $action = sanitize_input($_POST["action"] ?? "");
    $validToken = $_SERVER["REQUEST_METHOD"] !== "POST"
        || (
            isset($_POST["csrf_token"])
            && hash_equals($csrfToken, (string) $_POST["csrf_token"])
        );

    if (!$validToken) {
        $message = "Your session token expired. Refresh the page and try again.";
        $messageType = "error";
    } elseif ($action === "search") {
        $sql = "SELECT * FROM eoi";
        $types = "";
        $params = [];

        if ($searchRefnum !== "") {
            $cleanRef = strtoupper(preg_replace("/[^A-Za-z0-9]/", "", $searchRefnum));
            $sql .= " WHERE jobreferencenumber = ?";
            $types = "s";
            $params[] = $cleanRef;
        }

        $sql .= " ORDER BY " . $sortColumn . " ASC";

        [$resultRows, $queryError] = select_rows($connection, $sql, $types, $params);
        $resultTitle = $searchRefnum === ""
            ? "All applications"
            : "Applications for " . strtoupper(preg_replace("/[^A-Za-z0-9]/", "", $searchRefnum));
        $resultDescription = $selectedSort !== "" 
            ? "Results are ordered by " . str_replace("_", " ", $selectedSort) . "."
            : "Results are ordered by EOI number.";

        if ($queryError !== "") {
            $message = $queryError;
            $messageType = "error";
        }
    } elseif ($action === "list") {
        $conditions = [];
        $types = "";
        $params = [];

        if ($listRefnum !== "") {
            $conditions[] = "jobreferencenumber = ?";
            $types .= "s";
            $params[] = strtoupper(preg_replace("/[^A-Za-z0-9]/", "", $listRefnum));
        }

        if ($listFirstname !== "") {
            $conditions[] = "firstname LIKE ?";
            $types .= "s";
            $params[] = "%" . $listFirstname . "%";
        }

        if ($listLastname !== "") {
            $conditions[] = "lastname LIKE ?";
            $types .= "s";
            $params[] = "%" . $listLastname . "%";
        }

        $sql = "SELECT * FROM eoi";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY EOInumber DESC";

        [$resultRows, $queryError] = select_rows($connection, $sql, $types, $params);
        $resultTitle = empty($conditions) ? "All applicant records" : "Applicant search results";
        $resultDescription = empty($conditions)
            ? "Every expression of interest currently stored in the database."
            : "Records matching all of the supplied applicant filters.";

        if ($queryError !== "") {
            $message = $queryError;
            $messageType = "error";
        }
    } elseif ($action === "change") {
        $allowedStatuses = ["New", "Current", "Final"];

        if (!ctype_digit($statusEoiNumber) || !in_array($selectedStatus, $allowedStatuses, true)) {
            $message = "Enter a valid EOI number and choose an approved status.";
            $messageType = "error";
        } else {
            [$affectedRows, $writeError] = write_record(
                $connection,
                "UPDATE eoi SET status = ? WHERE EOInumber = ?",
                "si",
                [$selectedStatus, (int) $statusEoiNumber]
            );

            if ($writeError !== "") {
                $message = $writeError;
                $messageType = "error";
            } elseif ($affectedRows === 0) {
                $message = "No record was changed. Check the EOI number or current status.";
                $messageType = "info";
            } else {
                $message = "EOI #" . $statusEoiNumber . " is now marked as " . $selectedStatus . ".";
                $messageType = "success";
            }
        }
    } elseif ($action === "delete") {
        $normalisedDeleteRef = strtoupper(preg_replace("/[^A-Za-z0-9]/", "", $deleteRefnum));

        if (!preg_match("/^[A-Z0-9]{5}$/", $normalisedDeleteRef)) {
            $message = "Select a valid job reference number.";
            $messageType = "error";
        } else {
            [$affectedRows, $writeError] = write_record(
                $connection,
                "DELETE FROM eoi WHERE jobreferencenumber = ?",
                "s",
                [$normalisedDeleteRef]
            );

            if ($writeError !== "") {
                $message = $writeError;
                $messageType = "error";
            } elseif ($affectedRows === 0) {
                $message = "No applications were found for " . $normalisedDeleteRef . ".";
                $messageType = "info";
            } else {
                $message = $affectedRows . " application(s) for " . $normalisedDeleteRef . " were deleted successfully.";
                $messageType = "success";
                $deleteRefnum = "";
            }
        }
    }

    if (!in_array($action, ["search", "list"], true)) {
        [$resultRows, $queryError] = select_rows(
            $connection,
            "SELECT * FROM eoi ORDER BY EOInumber DESC"
        );
    
        if ($queryError !== "" && $message === "") {
            $message = $queryError;
            $messageType = "error";
        }
    }

    $dashboardCounts = load_dashboard_counts($connection);
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("includes/header.inc"); ?>
    <meta name="description" content="CezTech manager dashboard for reviewing and managing applicant expressions of interest.">
    <link rel="stylesheet" href="css/manage.css">
    <script src="scripts/manage.js" defer></script>
    <script src="scripts/enhancements.js" defer></script>

    <title>CezTech | Manage Applicants</title>
</head>

<body>
    <?php if ($message !== ""): ?>
        <div class="toast-notification-container" id="toastContainer">
            <div class="toast-notification toast-notification--<?php echo $messageType === 'success' ? 'success' : 'error'; ?>" role="status">
                <div class="toast-notification__content">
                    <span class="toast-notification__icon" aria-hidden="true">
                        <?php echo $messageType === "success" ? "✓" : "!"; ?>
                    </span>
                    <div class="toast-notification__text-wrap">
                        <strong class="toast-notification__title">
                            <?php 
                                if ($messageType === "success") {
                                    echo "Action Completed";
                                } elseif ($messageType === "error") {
                                    echo "Manager Notice";
                                } else {
                                    echo "Information";
                                }
                            ?>
                        </strong>
                        <p class="toast-notification__text"><?php echo h($message); ?></p>
                    </div>
                </div>
                <button type="button" class="toast-notification__close" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php include_once("includes/menu.inc"); ?>

        <main class="manager-main">
            <div class="manager-hero-wrapper">
                <section class="manager-hero-card" aria-labelledby="manager-title">
                    <p class="eyebrow">
                        <span class="eyebrow__dot" aria-hidden="true"></span>
                        Manager workspace
                    </p>
                    <h1 id="manager-title">Applicant management, organised in one workspace.</h1>
                    <p>
                        Search expressions of interest, review candidate details,
                        update application progress and maintain role records.
                    </p>
                </section>

                <div class="manager-account-card">
                    <div class="manager-card-badge">
                        <span class="status-pulse"></span>
                        <span>Active Session</span>
                    </div>

                    <div class="manager-avatar-wrapper">
                        <span class="manager-avatar" aria-hidden="true">
                            <?php echo h(strtoupper(substr($_SESSION["login"], 0, 1))); ?>
                        </span>
                        <span class="manager-avatar-shield" title="Authorized Administrator">✓</span>
                    </div>

                    <div class="manager-account-details">
                        <small>Signed in as</small>
                        <strong><?php echo h($_SESSION["login"]); ?></strong>
                        <span class="manager-role-tag">Portal Administrator</span>
                    </div>

                    <a href="logout.php" class="manager-logout-btn">
                        <span>Log out</span>
                        <svg class="logout-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </a>
                </div>
            </div>

            <section class="manager-stats reveal-on-scroll delay-100" aria-label="Application overview">
                <article>
                    <span class="manager-stat__label">All applications</span>
                    <strong class="counter" data-target="<?php echo (int)$dashboardCounts["total"]; ?>">0</strong>
                    <small>Total EOI records</small>
                </article>
                <article>
                    <span class="manager-stat__label manager-stat__label--new">New</span>
                    <strong class="counter" data-target="<?php echo (int)$dashboardCounts["new_count"]; ?>">0</strong>
                    <small>Awaiting review</small>
                </article>
                <article>
                    <span class="manager-stat__label manager-stat__label--current">Current</span>
                    <strong class="counter" data-target="<?php echo (int)$dashboardCounts["current_count"]; ?>">0</strong>
                    <small>In progress</small>
                </article>
                <article>
                    <span class="manager-stat__label manager-stat__label--final">Final</span>
                    <strong class="counter" data-target="<?php echo (int)$dashboardCounts["final_count"]; ?>">0</strong>
                    <small>Completed decisions</small>
                </article>
            </section>

            <section class="manager-tools reveal-on-scroll delay-200" aria-labelledby="manager-tools-title">
                <div class="manager-section-heading">
                    <div>
                        <p class="section-label">Management tools</p>
                        <h2 id="manager-tools-title">Choose an applicant action.</h2>
                    </div>
                    <p>
                        Each tool performs one focused task so records are easier and safer to manage.
                    </p>
                </div>

                <div class="manager-tool-grid">
                    <article class="manager-tool-card">
                        <div class="manager-tool-card__heading">
                            <span>01</span>
                            <div>
                                <h3>Search and sort</h3>
                                <p>View a role or sort the complete application table.</p>
                            </div>
                        </div>

                        <form method="post" action="manage.php" class="manager-form">
                            <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">

                            <div class="field-group">
                                <label for="search-refnum">Job reference number</label>
                                <select name="refnum" id="search-refnum" class="form-control">
                                    <option value="">Select role</option>
                                    <option value="JS388" <?php echo $searchRefnum === "JS388" ? "selected" : ""; ?>>JS388 - Desktop Support</option>
                                    <option value="JC257" <?php echo $searchRefnum === "JC257" ? "selected" : ""; ?>>JC257 - UX/UI Designer</option>
                                </select>
                            </div>

                            <div class="field-group">
                                <label for="sort">Sort results by</label>
                                <select name="sort" id="sort" class="form-control">
                                    <option value="" <?php echo $selectedSort === "" ? "selected" : ""; ?>>Select type</option>
                                    <option value="EOInumber" <?php echo $selectedSort === "EOInumber" ? "selected" : ""; ?>>EOI number</option>
                                    <option value="firstname" <?php echo $selectedSort === "firstname" ? "selected" : ""; ?>>First name</option>
                                    <option value="lastname" <?php echo $selectedSort === "lastname" ? "selected" : ""; ?>>Last name</option>
                                    <option value="address" <?php echo $selectedSort === "address" ? "selected" : ""; ?>>Address</option>
                                    <option value="suburb" <?php echo $selectedSort === "suburb" ? "selected" : ""; ?>>Suburb</option>
                                    <option value="state" <?php echo $selectedSort === "state" ? "selected" : ""; ?>>State</option>
                                    <option value="postcode" <?php echo $selectedSort === "postcode" ? "selected" : ""; ?>>Postcode</option>
                                    <option value="email" <?php echo $selectedSort === "email" ? "selected" : ""; ?>>Email</option>
                                    <option value="number" <?php echo $selectedSort === "number" ? "selected" : ""; ?>>Phone number</option>
                                    <option value="status" <?php echo $selectedSort === "status" ? "selected" : ""; ?>>Status</option>
                                </select>
                            </div>

                            <button type="submit" name="action" value="search" class="button button--primary manager-form__button"
                                    <?php echo !$databaseAvailable ? "disabled" : ""; ?>>
                                Search records <span aria-hidden="true">→</span>
                            </button>
                        </form>
                    </article>

                    <article class="manager-tool-card" id="listform">
                        <div class="manager-tool-card__heading">
                            <span>02</span>
                            <div>
                                <h3>Applicant lookup</h3>
                                <p>Filter records using one or more applicant details.</p>
                            </div>
                        </div>

                        <form method="post" action="manage.php" class="manager-form">
                            <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">

                            <div class="field-group">
                                <label for="list-refnum">Job reference number</label>
                                <select name="list_refnum" id="list-refnum" class="form-control">
                                    <option value="">Select role</option>
                                    <option value="JS388" <?php echo $listRefnum === "JS388" ? "selected" : ""; ?>>JS388 - Desktop Support</option>
                                    <option value="JC257" <?php echo $listRefnum === "JC257" ? "selected" : ""; ?>>JC257 - UX/UI Designer</option>
                                </select>
                            </div>

                            <div class="manager-form__row">
                                <div class="field-group">
                                    <label for="list-firstname">First name</label>
                                    <input
                                        type="text"
                                        name="firstname"
                                        id="list-firstname"
                                        class="form-control"
                                        value="<?php echo h($listFirstname); ?>"
                                        pattern="[A-Za-z]+" 
                                        title="Only letters are allowed"
                                        placeholder="Enter first name"
                                    >
                                </div>

                                <div class="field-group">
                                    <label for="list-lastname">Last name</label>
                                    <input
                                        type="text"
                                        name="lastname"
                                        id="list-lastname"
                                        class="form-control"
                                        value="<?php echo h($listLastname); ?>"
                                        pattern="[A-Za-z\s]+" 
                                        title="Only letters and spaces are allowed"
                                        placeholder="Enter last name"
                                    >
                                </div>
                            </div>

                            <button type="submit" name="action" value="list" class="button button--secondary manager-form__button"
                                    <?php echo !$databaseAvailable ? "disabled" : ""; ?>>
                                List applicants <span aria-hidden="true">→</span>
                            </button>
                        </form>
                    </article>

                    <article class="manager-tool-card">
                        <div class="manager-tool-card__heading">
                            <span>03</span>
                            <div>
                                <h3>Update application status</h3>
                                <p>Move an individual EOI through the recruitment workflow.</p>
                            </div>
                        </div>

                        <form method="post" action="manage.php" class="manager-form">
                            <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">

                            <div class="manager-form__row">
                                <div class="field-group">
                                    <label for="status-eoinumber">EOI number</label>
                                    <input
                                        type="number"
                                        name="eoinumber"
                                        id="status-eoinumber"
                                        class="form-control"
                                        value="<?php echo h($statusEoiNumber); ?>"
                                        min="1"
                                        required
                                    >
                                </div>

                                <div class="field-group">
                                    <label for="status">New status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select status</option>
                                        <option value="New" <?php echo $selectedStatus === "New" ? "selected" : ""; ?>>New</option>
                                        <option value="Current" <?php echo $selectedStatus === "Current" ? "selected" : ""; ?>>Current</option>
                                        <option value="Final" <?php echo $selectedStatus === "Final" ? "selected" : ""; ?>>Final</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="action" value="change" class="button button--primary manager-form__button"
                                    <?php echo !$databaseAvailable ? "disabled" : ""; ?>>
                                Update status <span aria-hidden="true">→</span>
                            </button>
                        </form>
                    </article>

                    <article class="manager-tool-card manager-tool-card--danger">
                        <div class="manager-tool-card__heading">
                            <span>04</span>
                            <div>
                                <h3>Delete role applications</h3>
                                <p>Permanently remove every EOI submitted for one job reference.</p>
                            </div>
                        </div>

                        <form method="post" action="manage.php" class="manager-form">
                            <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">

                            <div class="field-group">
                                <label for="delete-refnum">Job reference number</label>
                                <select name="delete_refnum" id="delete-refnum" class="form-control" required>
                                    <option value="" disabled <?php echo $deleteRefnum === "" ? "selected" : ""; ?>>Select role</option>
                                    <option value="JS388" <?php echo $deleteRefnum === "JS388" ? "selected" : ""; ?>>JS388 - Desktop Support</option>
                                    <option value="JC257" <?php echo $deleteRefnum === "JC257" ? "selected" : ""; ?>>JC257 - UX/UI Designer</option>
                                </select>
                            </div>

                            <label class="manager-confirmation">
                                <input type="checkbox" required>
                                <span>I understand this permanently deletes all matching applications.</span>
                            </label>

                            <button type="submit" name="action" value="delete" class="manager-danger-button"
                                    <?php echo !$databaseAvailable ? "disabled" : ""; ?>>
                                Delete matching EOIs
                            </button>
                        </form>
                    </article>
                </div>
            </section>

            <section class="manager-records reveal-on-scroll delay-300" aria-labelledby="records-title">
                <div class="manager-records__heading">
                    <div>
                        <p class="section-label">Applicant records</p>
                        <h2 id="records-title"><?php echo h($resultTitle); ?></h2>
                        <p><?php echo h($resultDescription); ?></p>
                    </div>
                    <span class="result-count">
                        <?php echo count($resultRows); ?> record<?php echo count($resultRows) === 1 ? "" : "s"; ?>
                    </span>
                </div>

                <?php if (!$databaseAvailable): ?>
                    <div class="manager-empty-state">
                        <strong>Database unavailable</strong>
                        <p>Check the connection values in <code>config.php</code>.</p>
                    </div>
                <?php elseif (empty($resultRows)): ?>
                    <div class="manager-empty-state">
                        <strong>No matching applications</strong>
                        <p>Adjust the filters above and run another search.</p>
                    </div>
                <?php else: ?>
                    <div class="manager-table-shell" tabindex="0" aria-label="Scrollable applicant records">
                        <table class="manager-table">
                            <thead>
                                <tr>
                                    <th scope="col">EOI</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Applicant</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Skills</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultRows as $row): ?>
                                    <?php
                                        $skills = applicant_skills($row);
                                        $statusClass = strtolower(preg_replace("/[^a-zA-Z]/", "", (string) ($row["status"] ?? "")));
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="record-id">#<?php echo h($row["EOInumber"] ?? ""); ?></span>
                                        </td>
                                        <td>
                                            <strong><?php echo h($row["jobreferencenumber"] ?? ""); ?></strong>
                                        </td>
                                        <td>
                                            <strong>
                                                <?php echo h(trim(($row["firstname"] ?? "") . " " . ($row["lastname"] ?? ""))); ?>
                                            </strong>
                                            <small><?php echo h($row["address"] ?? ""); ?></small>
                                        </td>
                                        <td>
                                            <?php if (!empty($row["email"])): ?>
                                                <a href="mailto:<?php echo h($row["email"]); ?>">
                                                    <?php echo h($row["email"]); ?>
                                                </a>
                                            <?php endif; ?>
                                            <small><?php echo h($row["number"] ?? ""); ?></small>
                                        </td>
                                        <td>
                                            <strong>
                                                <?php echo h(trim(($row["suburb"] ?? "") . ", " . ($row["state"] ?? ""), ", ")); ?>
                                            </strong>
                                            <small><?php echo h($row["postcode"] ?? ""); ?></small>
                                        </td>
                                        <td>
                                            <div class="skill-list">
                                                <?php if (empty($skills)): ?>
                                                    <span class="skill-chip skill-chip--empty">Not supplied</span>
                                                <?php else: ?>
                                                    <?php foreach ($skills as $skill): ?>
                                                        <span class="skill-chip"><?php echo h($skill); ?></span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-badge--<?php echo h($statusClass); ?>">
                                                <?php echo h($row["status"] ?: "Unassigned"); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <?php include_once("includes/footer.inc"); ?>
    </div>
</body>
</html>