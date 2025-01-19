<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] == 'manager') {
    $stmtUnread = $pdo->prepare("
    SELECT COUNT(*) AS unread_count
    FROM notifications n
    LEFT JOIN cafes c ON n.receiver_id = c.id
    WHERE c.manager_id = :id AND n.status = 'Unread'
");
    $stmtUnread->execute(['id' => $_SESSION['user_id']]);
    $unreadCount = $stmtUnread->fetch(PDO::FETCH_ASSOC)['unread_count'];

}
else {
    $stmtUnread = $pdo->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE receiver_id = :receiver_id AND status = 'unread'");
    $stmtUnread->execute(['receiver_id' => $_SESSION['user_id']]);
    $unreadCount = $stmtUnread->fetchColumn();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <li><a href="/gallery_cafe/public/manager/dashboard.php">Home</a></li>
                    <li><a href="/gallery_cafe/public/manager/view_foods.php">Foods</a></li>
                    <li><a href="/gallery_cafe/public/manager/view_orders.php">Orders</a></li>
                    <li><a href="/gallery_cafe/public/manager/view_tables.php">Tables</a></li>
                    <li><a href="/gallery_cafe/public/manager/approve_reservations.php">Reservation</a></li>
                    <li><a href="/gallery_cafe/public/manager/view_complaints.php">Complaints</a></li>
                    <li><a href="/gallery_cafe/includes/auth.php?logout=true">Logout</a></li>
                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
                    <li>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></li>
                    <li><a href="/gallery_cafe/public/admin/dashboard.php">Home</a></li>
                    <li><a href="/gallery_cafe/public/admin/manage_cafes.php">Cafes</a></li>
                    <li><a href="/gallery_cafe/public/admin/view_complaints.php">Complaints</a></li>
                    <li><a href="/gallery_cafe/includes/auth.php?logout=true">Logout</a></li>
                <?php else:?>
                    <li><a href="/gallery_cafe/public/login.php">Login</a></li>
                    <li><a href="/gallery_cafe/public/register.php">Register</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])){?>
                <li>
                    <a href="/gallery_cafe/public/notification.php" style="position: relative;">
                        <img src="/gallery_cafe/assets/images/bell-regular.svg" alt="Notifications" style="width: 24px; height: 24px;">
                        <?php if ($unreadCount > 0): ?>
                            <span style="
                                    position: absolute;
                                    top: 0;
                                    right: 0;
                                    background: red;
                                    color: white;
                                    font-size: 12px;
                                    padding: 2px 5px;
                                    border-radius: 50%;
                                ">
                                    <?= $unreadCount; ?>
                                </span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php }?>
            </ul>
        </nav>
    </div>
</header>
