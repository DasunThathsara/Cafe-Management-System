<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

if ($_SESSION['role'] !== 'manager') {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT n.* FROM notifications n LEFT JOIN cafes c ON n.receiver_id = c.manager_id WHERE c.idid = :id ORDER BY created_at DESC");
    $stmt->execute(['id' => $userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE receiver_id = :id ORDER BY created_at DESC");
    $stmt->execute(['id' => $userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_as_read'])) {
        $stmt = $pdo->prepare("UPDATE notifications SET status = 'Read' WHERE id = :id");
        $stmt->execute(['id' => $_POST['notification_id']]);
    }
    header("Location: notification.php");
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
        border: none;
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
        width: 100px;
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

    .container {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    footer {
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .cafe-section {
        margin-top: 100px;
        height: 100%;
        width: 1000px;
        border-radius: 20px;
        box-shadow: 0 0 10px 0.1px rgba(0, 0, 0, 0.16);
    }

    .table-container {
        width: 100%;
        height: 100%;
        overflow: scroll;
    }
</style>

<div class="container">
    <div class="cafe-section">
        <h2 class="title">Notifications</h2>
        <div class="table-container">
            <table border="1">
                <tr>
                    <th>Notification ID</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?= $notification['id'] ?></td>
                        <td><?= htmlspecialchars($notification['message']) ?></td>
                        <td><?= $notification['created_at'] ?></td>
                        <td><?= htmlspecialchars($notification['status']) ?></td>
                        <td>
                            <?php if ($notification['status'] === 'Unread'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="notification_id" value="<?= $notification['id'] ?>">
                                    <button type="submit" name="mark_as_read">Mark as Read</button>
                                </form>
                            <?php else: ?>
                                <button disabled>Read</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
