<?php
session_start();
$redirectPage = isset($_SESSION['admin_logged_in']) ? 'admin_login.php' : 'login.php';
session_unset();
session_destroy();
header("Location: $redirectPage");
exit;
?>
