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
$stmt = $pdo->prepare("
    SELECT r.id, r.customer_name, r.start_time, r.end_time, r.status, t.table_number
    FROM reservations r
    INNER JOIN cafes c ON r.manager_id = c.manager_id
    LEFT JOIN tables t ON r.table_id = t.id
    WHERE c.manager_id = :manager_id AND r.status = 'PENDING'
    ORDER BY r.start_time
");

$stmt->execute(['manager_id' => $managerId]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_reservation'])) {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'Approved' WHERE id = :id");
        $stmt->execute(['id' => $_POST['reservation_id']]);
    } elseif (isset($_POST['reject_reservation'])) {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'Rejected' WHERE id = :id");
        $stmt->execute(['id' => $_POST['reservation_id']]);
    }
    header("Location: approve_reservations.php");
    exit;
}
?>
<h2>Approve Reservations</h2>
<table border="1">
    <tr>
        <th>Reservation ID</th>
        <th>Customer Name</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Table Number</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($reservations as $reservation): ?>
        <tr>
            <td><?= $reservation['id'] ?></td>
            <td><?= htmlspecialchars($reservation['customer_name']) ?></td>
            <td><?= $reservation['start_time'] ?></td>
            <td><?= $reservation['end_time'] ?></td>
            <td><?= $reservation['table_number'] ?></td>
            <td><?= htmlspecialchars($reservation['status']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                    <button type="submit" name="approve_reservation">Approve</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                    <button type="submit" name="reject_reservation">Reject</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../../includes/footer.php'; ?>
