<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM cafes WHERE status = 'APPROVED'");
$stmt->execute();
$cafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    $stmt = $pdo->prepare("INSERT INTO complaints (customer_id, cafe_id, complaint_text, status) VALUES (:customer_id, :cafe_id, :complaint_text, 'PENDING')");
    $stmt->execute([
        'customer_id' => $_SESSION['user_id'],
        'cafe_id' => $_POST['cafe_id'],
        'complaint_text' => $_POST['complaint']
    ]);
    header("Location: dashboard.php");
    exit;
}
?>
<h2>Submit Complaint</h2>
<form method="post">
    <textarea name="complaint" rows="5" cols="30" placeholder="Enter your complaint" required></textarea>
    <select name="cafe_id" required>
        <option value="" hidden>Select Café</option>
        <?php foreach ($cafes as $row) { ?>
            <option value="<?php echo $row['id']?>" ><?php echo $row['name']?></option>
        <?php } ?>
    </select>
    <button type="submit" name="submit_complaint">Submit</button>
</form>
<?php require_once '../../includes/footer.php'; ?>
