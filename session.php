<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
   session_start();
}

if (!isset($_SESSION['login'])) {
   header("Location: login.php");
   exit();
}

include('config.php');
$db = ceztech_db_connect();

if ($db) {
   mysqli_set_charset($db, "utf8mb4");
    
   $stmt = mysqli_prepare($db, "SELECT username FROM manager WHERE username = ? LIMIT 1");

   if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $_SESSION['login']);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $username);
        
      if (!mysqli_stmt_fetch($stmt)) {
         mysqli_stmt_close($stmt);
         mysqli_close($db);
         session_destroy();
         header("Location: login.php");
         exit();
      }
      mysqli_stmt_close($stmt);
   }
}
?>