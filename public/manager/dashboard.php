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
<h2>Manager Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
<div>
    <p>Total Foods: <?= $totalFoods ?></p>
    <p>Total Tables: <?= $totalTables ?></p>
</div>
<a href="approve_reservations.php">Approve Reservations</a>
<?php require_once '../../includes/footer.php'; ?>
