<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST["refnum"])) {
    header('Location: apply.php');
    exit;
}

function sanitise_input($data) {
    if (is_array($data)) {
        return array_map('sanitise_input', $data);
    }
    $data = trim((string)$data);		
    $data = stripslashes($data);	
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

$errMsg = "";

$refnum = strtoupper(preg_replace("/[^A-Za-z0-9]/", "", $_POST["refnum"] ?? ""));
if (!preg_match("/^[A-Z0-9]{5}$/", $refnum)) {
    $errMsg .= "Invalid Job Reference Number (must be 5 alphanumeric characters). ";
}

$firstname = sanitise_input($_POST["firstname"] ?? "");
if (!preg_match("/^[a-zA-Z]{1,20}$/", $firstname)) {
    $errMsg .= "Invalid First Name (max 20 characters, letters only). ";
}

$lastname = sanitise_input($_POST["lastname"] ?? "");
if (!preg_match("/^[a-zA-Z\s]{1,20}$/", $lastname)) {
    $errMsg .= "Invalid Last Name (max 20 characters, letters only). ";
}

$dob = sanitise_input($_POST["dob"] ?? "");
$dobDate = DateTime::createFromFormat('Y-m-d', $dob);
if (!$dobDate) {
    $dobDate = DateTime::createFromFormat('d/m/Y', $dob);
}

$dobFormatted = "";
if (!$dobDate) {
    $errMsg .= "Invalid Date of Birth. ";
} else {
    $dobFormatted = $dobDate->format('Y-m-d');
    $currentDate = new DateTime();
    $age = $currentDate->diff($dobDate)->y;

    if ($age < 15 || $age > 80) {
        $errMsg .= "You must be between 15 and 80 years old. ";
    }
}

$gender = "";
$rawGender = sanitise_input($_POST["gender"] ?? "");
if (!in_array($rawGender, ["M", "F"], true)) {
    $errMsg .= "Please select a valid gender. ";
} else {
    $gender = $rawGender;
}

$address = sanitise_input($_POST["address"] ?? "");
if (empty($address) || strlen($address) > 40 || !preg_match("/^[A-Za-z0-9\s\/\.\,\-]+$/", $address)) {
    $errMsg .= "Street address is required (max 40 valid characters). ";
}

$suburb = sanitise_input($_POST["suburb"] ?? "");
if (empty($suburb) || strlen($suburb) > 40 || !preg_match("/^[A-Za-z\s]+$/", $suburb)) {
    $errMsg .= "Suburb is required (letters only, max 40 characters). ";
}

$state = "";
$rawState = sanitise_input($_POST["state"] ?? "");
$allowedStates = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
if (!in_array($rawState, $allowedStates, true)) {
    $errMsg .= "Please select a valid state. ";
} else {
    $state = $rawState;
}

$postcode = preg_replace("/[^0-9]/", "", $_POST["postcode"] ?? "");
if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errMsg .= "Invalid Postcode (must be 4 digits). ";
} else if (!empty($state)) {
    $firstDigit = $postcode[0];
    $matched = false;

    switch ($state) {
        case "VIC": $matched = ($firstDigit === "3" || $firstDigit === "8"); break;
        case "NSW": $matched = ($firstDigit === "1" || $firstDigit === "2"); break;
        case "QLD": $matched = ($firstDigit === "4" || $firstDigit === "9"); break;
        case "NT":  $matched = ($firstDigit === "0"); break;
        case "WA":  $matched = ($firstDigit === "6"); break;
        case "SA":  $matched = ($firstDigit === "5"); break;
        case "TAS": $matched = ($firstDigit === "7"); break;
        case "ACT": $matched = ($firstDigit === "0"); break;
    }

    if (!$matched) {
        $errMsg .= "Postcode ($postcode) and State ($state) do not match. ";
    }
}

$email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[A-Za-z0-9.@_-]+$/", $email)) {
    $errMsg .= "Invalid Email Address. ";
}

$number = preg_replace("/[^0-9]/", "", $_POST["number"] ?? "");
if (!preg_match("/^[0-9]{8,12}$/", $number)) {
    $errMsg .= "Invalid Phone Number (8 to 12 digits). ";
}

$skill = isset($_POST["skill"]) && is_array($_POST["skill"]) ? sanitise_input($_POST["skill"]) : [];
$otherskills = isset($_POST["otherskills"]) ? sanitise_input($_POST["otherskills"]) : "";

if (in_array("other skills", $skill, true) && empty($otherskills)) {
    $errMsg .= "Please briefly describe your other skills. ";
}

$skill1 = in_array("troubleshooting", $skill, true) ? "Troubleshooting Skill" : "";
$skill2 = in_array("technical proficiency", $skill, true) ? "Technical Proficiency" : "";
$skill3 = in_array("detail oriented", $skill, true) ? "Detail-Oriented" : "";
$skill4 = in_array("emotional intelligence", $skill, true) ? "Emotional Intelligence" : "";
$skill5 = in_array("other skills", $skill, true) ? "Other Skills" : "";

if (!empty($errMsg)) {
    $_SESSION["toast_error"] = trim($errMsg);
    header("Location: apply.php");
    exit;
}

require_once('config.php');

$conn = ceztech_db_connect();

if (!$conn) {
    $_SESSION["toast_error"] = "Database connection failure. Please try again later.";
    header("Location: apply.php");
    exit;
}

mysqli_set_charset($conn, "utf8mb4");

$sql_table = "eoi";

$create_table = "CREATE TABLE IF NOT EXISTS $sql_table (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    jobreferencenumber VARCHAR(5),
    firstname VARCHAR(20),
    lastname VARCHAR(20),
    dob DATE,
    gender VARCHAR(10),
    address VARCHAR(40),
    suburb VARCHAR(40),
    state VARCHAR(3),
    postcode VARCHAR(4),
    email VARCHAR(100),
    number VARCHAR(15),
    skill1 VARCHAR(25),
    skill2 VARCHAR(25),
    skill3 VARCHAR(25),
    skill4 VARCHAR(25),
    skill5 VARCHAR(25),
    otherskills TEXT,
    status ENUM('New', 'Current', 'Final') DEFAULT 'New'
)";

@mysqli_query($conn, $create_table);

try { mysqli_query($conn, "ALTER TABLE $sql_table ADD dob DATE"); } catch (mysqli_sql_exception $e) {}
try { mysqli_query($conn, "ALTER TABLE $sql_table ADD gender VARCHAR(10)"); } catch (mysqli_sql_exception $e) {}

$query = "INSERT INTO $sql_table
    (jobreferencenumber, firstname, lastname, dob, gender, address, suburb, state, postcode, email, number, skill1, skill2, skill3, skill4, skill5, otherskills, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssssssss",
        $refnum,
        $firstname,
        $lastname,
        $dobFormatted,
        $gender,
        $address,
        $suburb,
        $state,
        $postcode,
        $email,
        $number,
        $skill1,
        $skill2,
        $skill3,
        $skill4,
        $skill5,
        $otherskills
    );

    if (mysqli_stmt_execute($stmt)) {
        $EOInumber = mysqli_insert_id($conn);
        $_SESSION["toast_success"] = "Application Received! Thank you $firstname, your EOI ID is #" . $EOInumber . ".";
    } else {
        $_SESSION["toast_error"] = "Error saving application. Please try again.";
    }

    mysqli_stmt_close($stmt);
} else {
    $_SESSION["toast_error"] = "Failed to prepare database statement.";
}

mysqli_close($conn);

header("Location: apply.php");
exit;