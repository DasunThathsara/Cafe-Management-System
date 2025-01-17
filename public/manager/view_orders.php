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

// Fetch orders assigned to this manager's café
$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT o.*, CONCAT(u.first_name, ' ', u.last_name) AS customer_name, c.name AS cafe_name
    FROM orders o
    LEFT JOIN users u ON u.id = o.manager_id
    INNER JOIN cafes c ON u.id = c.manager_id
    WHERE c.manager_id = :manager_id AND o.status IN ('PENDING', 'PROCESSING')
    ORDER BY o.created_at DESC
");
$stmt->execute(['manager_id' => $managerId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle order status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->execute([
        'status' => $_POST['status'],
        'id' => $_POST['order_id']
    ]);
    header("Location: view_orders.php");
    exit;
}
?>
<h2>View Orders</h2>
<table border="1">
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Cafe Name</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['cafe_name'], 2) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td><?= $order['created_at'] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <select name="status" required>
                        <option value="PENDING" <?= $order['status'] === 'PENDING' ? 'selected' : '' ?>>Pending</option>
                        <option value="PROCESSING" <?= $order['status'] === 'PROCESSING' ? 'selected' : '' ?>>Processing</option>
                        <option value="COMPLETED" <?= $order['status'] === 'COMPLETED' ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <button type="submit" name="update_order_status">Update</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../../includes/footer.php'; ?>
