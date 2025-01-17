<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Handle logout
if (isset($_GET['logout'])) {
    print_r("hello");
    session_unset();
    session_destroy();
    header("Location: ../public/login.php");
    exit;
}
