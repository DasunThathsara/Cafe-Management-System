<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all café registration requests
$stmt = $pdo->query("SELECT * FROM cafes");
$pendingCafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Approve or reject café requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cafeId = $_POST['cafe_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'APPROVED' WHERE id = :id");
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'REJECTED' WHERE id = :id");
    } elseif ($action === 'ban') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'BANNED' WHERE id = :id");
    } elseif ($action === 'unban') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'APPROVED' WHERE id = :id");
    }

    $stmt->execute(['id' => $cafeId]);
    header("Location: manage_cafes.php");
    exit;
}
?>
<h2>Manage Cafés</h2>
<?php
// Include the meal card component
require "../components/adminResturantcard.php";

foreach ($pendingCafes as $row) {
    render_meal_card($row);
}
?>
</table>
<?php require_once '../../includes/footer.php'; ?>
