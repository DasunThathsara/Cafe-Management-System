<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM cafes WHERE status = 'APPROVED'");
$stmt->execute();
$cafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT t.id, t.table_number, c.name AS cafe_name, t.status
    FROM tables t
    INNER JOIN cafes c ON t.manager_id = c.manager_id
    WHERE t.status = 'Available'
");
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_reservation'])) {
    $table_id = $_POST['table_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $pdo->prepare("
        SELECT * FROM reservations 
        WHERE table_id = :table_id 
          AND status = 'ACCEPTED'
          AND (
            (start_time <= :end_time AND end_time >= :start_time)
          )
    ");
    $stmt->execute([
        'table_id' => $table_id,
        'start_time' => $start_time,
        'end_time' => $end_time
    ]);

    if ($stmt->rowCount() > 0) {
        echo "This table is already reserved for the selected time period.";
    } else {
        print_r($_POST);
        $stmt = $pdo->prepare("
            INSERT INTO reservations (table_id, customer_name, customer_phone, start_time, end_time, manager_id) 
            VALUES (:table_id, :customer_name, :customer_phone, :start_time, :end_time, :manager_id)
        ");
        $stmt->execute([
            'table_id' => $table_id,
            'customer_name' => $_POST['customer_name'],
            'customer_phone' => $_POST['customer_phone'],
            'start_time' => $start_time,
            'end_time' => $end_time,
            'manager_id' => $_POST['manager_id']
        ]);
        echo "Reservation request submitted.";
    }

    header("Location: dashboard.php");
    exit;
}
?>
<h2>Make Reservation</h2>
<form method="post">
    <select name="manager_id" required>
        <option value="" hidden>Select Café</option>
        <?php foreach ($cafes as $row) { ?>
            <option value="<?php echo $row['manager_id']?>" ><?php echo $row['name']?></option>
        <?php } ?>
    </select>

    <select name="table_id" required>
        <option value="" hidden>Table Number</option>
        <?php foreach ($tables as $row) { ?>
            <option value="<?php echo $row['id']?>" ><?php echo $row['table_number']?></option>
        <?php } ?>
    </select>
    <input type="text" name="customer_name" placeholder="Your Name" required>
    <input type="text" name="customer_phone" placeholder="Your Phone" required>
    <label for="start_time">Start Time:</label>
    <input type="datetime-local" name="start_time" required>
    <label for="end_time">End Time:</label>
    <input type="datetime-local" name="end_time" required>
    <button type="submit" name="make_reservation">Reserve Table</button>
</form>

<?php require_once '../../includes/footer.php'; ?>
