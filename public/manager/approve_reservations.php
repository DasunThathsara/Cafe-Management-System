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
        <h2 class="title">Reservations</h2>
        <div class="table-container">
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
        </div>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
