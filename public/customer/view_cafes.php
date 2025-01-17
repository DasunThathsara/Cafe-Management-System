<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Fetch all approved cafes
$stmt = $pdo->prepare("SELECT * FROM cafes WHERE status = 'APPROVED'");
$stmt->execute();
$cafes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array
?>

<h2>Available Cafes</h2>

<?php
// Include the meal card component
require "../components/resturantcard.php";

foreach ($cafes as $row) {
    render_meal_card($row);
}
?>

<?php require_once '../../includes/footer.php'; ?>
