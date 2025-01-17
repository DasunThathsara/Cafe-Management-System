<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is logged in and is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch stats for dashboard
$stmt = $pdo->query("SELECT COUNT(*) AS total_cafes FROM cafes");
$totalCafes = $stmt->fetch(PDO::FETCH_ASSOC)['total_cafes'];

$stmt = $pdo->query("SELECT COUNT(*) AS total_complaints FROM complaints");
$totalComplaints = $stmt->fetch(PDO::FETCH_ASSOC)['total_complaints'];
?>
<style>
    /* Admin Dashboard Styles */
    :root {
        --primary-color: #ff8000;
        --secondary-color: #6c757d;
        --background-color: #f4f4f4;
        --card-background: #ffffff;
        --text-color: #333;
        --border-radius: 10px;
    }

    .admin-dashboard {
        max-width: 1000px;
        margin: 30px auto;
        padding: 25px;
        background-color: var(--card-background);
        border-radius: var(--border-radius);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .dashboard-title {
        text-align: center;
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 600;
        position: relative;
        padding-bottom: 10px;
    }

    .dashboard-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background-color: var(--primary-color);
    }

    .dashboard-welcome {
        text-align: center;
        color: var(--secondary-color);
        font-size: 1.2rem;
        margin-bottom: 30px;
        font-style: italic;
    }

    .dashboard-stats {
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 250px;
        max-width: 350px;
        background-color: var(--background-color);
        border-radius: var(--border-radius);
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: var(--primary-color);
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .stat-card h3 {
        color: var(--secondary-color);
        margin-bottom: 15px;
        font-size: 1.3rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        color: var(--primary-color);
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .stat-number::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: var(--primary-color);
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .admin-dashboard {
            padding: 15px;
            margin: 15px;
        }

        .dashboard-title {
            font-size: 2rem;
        }

        .dashboard-stats {
            flex-direction: column;
            align-items: center;
        }

        .stat-card {
            width: 100%;
            max-width: 400px;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        :root {
            --background-color: #1e1e1e;
            --card-background: #2c2c2c;
            --text-color: #e0e0e0;
            --primary-color: #ffb24d;
        }

        .admin-dashboard {
            background-color: var(--card-background);
        }

        .dashboard-title {
            color: var(--primary-color);
        }

        .stat-card {
            background-color: #3a3a3a;
        }

        .stat-number {
            color: var(--primary-color);
        }
    }

    /* Accessibility Enhancements */
    @media (prefers-reduced-motion: reduce) {
        .admin-dashboard,
        .stat-card {
            transition: none;
        }
    }

    /* Additional Decorative Elements */
    .admin-dashboard::before {
        content: 'Admin Panel';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    /* Print Styles */
    @media print {
        .admin-dashboard {
            box-shadow: none;
            border: 1px solid #000;
        }
    }
</style>
<div class="admin-dashboard">
    <h2 class="dashboard-title">Admin Dashboard</h2>
    <p class="dashboard-welcome">Welcome, Admin!</p>
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Cafés</h3>
            <p class="stat-number"><?= $totalCafes ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Complaints</h3>
            <p class="stat-number"><?= $totalComplaints ?></p>
        </div>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
