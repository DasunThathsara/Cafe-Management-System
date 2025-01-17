<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is logged in and is a café manager
if ($_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

// Fetch tables managed by this manager
$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tables WHERE manager_id = :manager_id");
$stmt->execute(['manager_id' => $managerId]);
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add, update, and delete table actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_table'])) {
        $stmt = $pdo->prepare("INSERT INTO tables (table_number, seats, manager_id, status) VALUES (:table_number, :seats, :manager_id, 'AVAILABLE')");
        $stmt->execute([
            'table_number' => $_POST['table_number'],
            'seats' => $_POST['capacity'],
            'manager_id' => $managerId
        ]);
    }
    header("Location: view_tables.php");
    exit;
}
?>

<h2>Manage Tables</h2>
<form method="post">
    <input type="text" name="table_number" placeholder="Table Number" required>
    <input type="number" name="capacity" placeholder="Capacity" required>
    <button type="submit" name="add_table">Add Table</button>
</form>

<?php require_once '../../includes/footer.php'; ?>
