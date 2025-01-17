<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Fetch available tables for reservation
$stmt = $pdo->prepare("
    SELECT t.id, t.table_number, c.name AS cafe_name
    FROM tables t
    INNER JOIN cafes c ON t.cafe_id = c.id
    WHERE t.status = 'Available'
");
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_reservation'])) {
    $stmt = $pdo->prepare("
        INSERT INTO reservations (table_id, customer_id, reservation_time, status)
        VALUES (:table_id, :customer_id, :reservation_time, 'Pending')
    ");
    $stmt->execute([
        'table_id' => $_POST['table_id'],
        'customer_id' => $_SESSION['user_id'],
        'reservation_time' => $_POST['reservation_time']
    ]);
    header("Location: dashboard.php");
    exit;
}
?>
<h2>Make Reservation</h2>
<form method="post">
    <select name="table_id" required>
        <option value="">Select Table</option>
        <?php foreach ($tables as $table): ?>
            <option value="<?= $table['id']; ?>">Table <?= $table['table_number']; ?> at <?= htmlspecialchars($table['cafe_name']); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="datetime-local" name="reservation_time" required>
    <button type="submit" name="make_reservation">Reserve</button>
</form>
<?php require_once '../../includes/footer.php'; ?>
