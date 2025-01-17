<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $stmt = $pdo->prepare("INSERT INTO reviews (customer_id, cafe_id, review, rating) VALUES (:customer_id, :cafe_id, :review, :rating)");
    $stmt->execute([
        'customer_id' => $_SESSION['user_id'],
        'cafe_id' => $_POST['cafe_id'],
        'review' => $_POST['review'],
        'rating' => $_POST['rating']
    ]);
    header("Location: dashboard.php");
    exit;
}
?>
<h2>Write Review</h2>
<form method="post">
    <textarea name="review" rows="5" cols="30" placeholder="Enter your review" required></textarea>
    <select name="cafe_id" required>
        <option value="">Select Café</option>
        <!-- Populate with cafes from the database -->
    </select>
    <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
    <button type="submit" name="submit_review">Submit</button>
</form>
<?php require_once '../../includes/footer.php'; ?>
