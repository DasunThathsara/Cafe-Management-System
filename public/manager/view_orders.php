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
<style>
    .title {
        text-align: center;
        color: #333;
        padding: 25px 0;
        font-size: 2.2rem;
        background: linear-gradient(to right, #ff8c00, #ff5900);
        color: white;
        margin: 0;
        border-radius: 20px 20px 0 0;
    }

    table {
        width: 90%;
        margin: 50px auto;
        border-collapse: collapse;
        border-spacing: 0;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: none;;
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border: none;
    }

    th {
        background-color: #ff9900;
        color: white;
        font-weight: bold;
    }

    td {
        background-color: rgba(255, 235, 204, 0.6);
        color: #555;
    }

    tr:nth-child(even) td {
        background: rgba(255, 238, 204, 0.21);
    }

    .btn-4, button {
        width: 80px;
        background-color: #ff8c00;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 5px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-4:hover, button:hover {
        background-color: #e07b00;
    }

    @media (max-width: 768px) {
        table {
            font-size: 0.9rem;
        }

        h2 {
            font-size: 1.5rem;
        }
    }

    .container{
        display: flex;
        justify-content: center;
        width: 100%;
    }

    footer{
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .cafe-section{
        margin-top: 100px;
        height: 100%;
        width: 1000px;
        border-radius: 20px;
        box-shadow: 0 0 10px 0.1px rgba(0, 0, 0, 0.16);
    }

    .table-container{
        width: 100%;
        height: 100%;
        overflow: scroll;
    }
</style>

<div class="container">
    <div class="cafe-section">
        <h2 class="title">Orders</h2>
        <div class="table-container">
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
        </div>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
