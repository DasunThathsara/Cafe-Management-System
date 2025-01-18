<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->query("SELECT c.*, ca.name AS cafe_name FROM complaints c LEFT JOIN cafes ca ON c.cafe_id = ca.id ORDER BY id DESC");
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

function timeAgo($datetime) {
    date_default_timezone_set('Asia/Colombo');
    $time = strtotime($datetime);

    if ($time === false) {
        return 'Invalid date';
    }

    $diff = time() - $time;

    if ($diff < 0) {
        return 'In the future';
    }

    if ($diff < 60) return $diff . ' seconds ago';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
    return date('F j, Y', $time);
}

?>
<style>
    .title {
        text-align: center;
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

    button {
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

    button:hover {
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
    }

    footer {
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .complaint-section {
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
    <div class="complaint-section">
        <h2 class="title">Complaints</h2>
        <div class="table-container">
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
                        <td><?php echo timeAgo($complaint['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
