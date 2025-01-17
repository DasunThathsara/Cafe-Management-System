<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';
?>
<h1>Welcome to the Café Management System</h1>
<p>Please <a href="login.php">Login</a> or <a href="register.php">Register</a> to continue.</p>
<?php require_once '../includes/footer.php'; ?>
