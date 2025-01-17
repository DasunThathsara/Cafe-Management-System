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
</head>
<body>
<header>
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
            background-color:w #d35400;
        }
    </style>

    <div class="container">
        <div style="display: flex; gap: 12px; align-items: center;">
            <img src="/gallery_cafe/assets/images/logo.png" style="width: 35px; height: 35px;" alt="logo-gallery cafe">
            <h1>The Gallery Cafe</h1>
        </div>

        <nav>
            <ul>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'):?>
                    <li>Welcome, <?= htmlspecialchars($_SESSION['username']);?></li>
                    <li><a href="../../public/customer/dashboard.php">Home</a></li>
                    <li><a href="../../public/customer/view_cafes.php">Cafes</a></li>
                    <li><a href="../../public/customer/order_food.php">Foods</a></li>
                    <li><a href="../../public/customer/make_reservation.php">Reservation</a></li>
                    <li><a href="../../public/customer/complaints.php">Complaints</a></li>
                    <li><a href="/gallery_cafe/includes/auth.php?logout=true">Logout</a></li>
                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == 'manager'):?>
                    <li>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></li>
                    <li><a href="../../public/manager/dashboard.php">Home</a></li>
                    <li><a href="../../public/manager/view_foods.php">Foods</a></li>
                    <li><a href="../../public/manager/view_orders.php">Orders</a></li>
                    <li><a href="../../public/manager/view_tables.php">Tables</a></li>
                    <li><a href="../../public/manager/make_reservation.php">Reservation</a></li>
                    <li><a href="../../public/manager/view_complaints.php">Complaints</a></li>
                    <li><a href="/gallery_cafe/includes/auth.php?logout=true">Logout</a></li>
                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
                    <li>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></li>
                    <li><a href="../../public/admin/dashboard.php">Home</a></li>
                    <li><a href="../../public/admin/manage_cafes.php">Cafes</a></li>
                    <li><a href="../../public/admin/view_complaints.php">Complaints</a></li>
                    <li><a href="/gallery_cafe/includes/auth.php?logout=true">Logout</a></li>
                <?php else:?>
                    <li><a href="../public/login.php">Login</a></li>
                    <li><a href="../public/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
