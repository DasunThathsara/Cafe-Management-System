<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

if ($_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT COUNT(*) AS totalFoods FROM foods WHERE manager_id = :manager_id");
$stmt->execute(['manager_id' => $managerId]);
$totalFoods = $stmt->fetch(PDO::FETCH_ASSOC)['totalFoods'];

$stmt = $pdo->prepare("SELECT COUNT(*) AS totalTables FROM tables WHERE manager_id = :manager_id");
$stmt->execute(['manager_id' => $managerId]);
$totalTables = $stmt->fetch(PDO::FETCH_ASSOC)['totalTables'];
?>
<style>
    /* Manager Dashboard Styles */
    :root {
        --primary-color: #ff8000;
        --secondary-color: #6c757d;
        --background-color: #f4f4f4;
        --card-background: #ffffff;
        --text-color: #333;
        --border-radius: 10px;
    }

    .manager-dashboard {
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
        .manager-dashboard {
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

        .manager-dashboard {
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
        .manager-dashboard,
        .stat-card {
            transition: none;
        }
    }
</style>

<div class="manager-dashboard">
    <h2 class="dashboard-title">Manager Dashboard</h2>
    <p class="dashboard-welcome">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Total Foods</h3>
            <p class="stat-number"><?= $totalFoods ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Tables</h3>
            <p class="stat-number"><?= $totalTables ?></p>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
