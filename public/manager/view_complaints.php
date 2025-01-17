<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

if ($_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT c.*, ca.name AS cafe_name FROM complaints c LEFT JOIN cafes ca ON c.cafe_id = ca.id ORDER BY id DESC");
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Complaints</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Café</th>
        <th>Complaint</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php foreach ($complaints as $complaint): ?>
        <tr>
            <td><?php echo $complaint['id']; ?></td>
            <td><?php echo htmlspecialchars($complaint['customer_id']); ?></td>
            <td><?php echo htmlspecialchars($complaint['cafe_name']); ?></td>
            <td><?php echo htmlspecialchars($complaint['complaint_text']); ?></td>
            <td><?php echo htmlspecialchars($complaint['status']); ?></td>
            <td><?php echo htmlspecialchars($complaint['created_at']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../../includes/footer.php'; ?>
