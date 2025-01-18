<?php
session_start();
require_once 'config/database.php';
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Management System</title>
    <link rel="icon" href="/gallery_cafe/assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/gallery_cafe/assets/css/style.css">

    <style>
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        header .container {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .search-bar {
            margin-left: 20px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 8px;
            border-radius: 4px;
            border: none;
            font-size: 16px;
            width: 200px;
        }

        .search-bar button {
            background-color: #e67e22;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 16px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #d35400;
        }

        footer{
            position: sticky;
            bottom: 0;
        }
    </style>
</head>
<body>
<header>


    <div class="container">
        <div style="display: flex; gap: 12px; align-items: center;">
            <img src="/gallery_cafe/assets/images/logo.png" style="width: 35px; height: 35px;" alt="logo-gallery cafe">
            <h1>The Gallery Cafe</h1>
        </div>

        <nav>
            <ul>
                <li><a href="./public/login.php">Login</a></li>
                <li><a href="./public/register.php">Register</a></li>
            </ul>
        </nav>
    </div>
</header>

<div style="width: 100vw; height: 100vh; display: flex; justify-content: center">
    <div style="box-shadow: 0 0 10px 0.1px rgba(0,0,0,0.11); border-radius: 20px; padding: 50px; height: 50%; margin-top: 60px">
        <h1 style="color: #e07b00">Welcome to the Café Management System</h1>
        <div style="display: flex; justify-content: center; padding-top: 80px">
            <a style="background: #e07b00; color: white; border-radius: 10px; padding: 10px 50px; text-decoration: none;" href="./public/login.php">Login</a>
            &nbsp;&nbsp;
            <a style="background: #e07b00; color: white; border-radius: 10px; padding: 10px 50px; text-decoration: none;" href="./public/register.php">Register</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
