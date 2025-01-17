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

$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM foods WHERE manager_id = :manager_id");
$stmt->execute(['manager_id' => $managerId]);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_food'])) {
    $stmt = $pdo->prepare("DELETE FROM foods WHERE id = :id AND manager_id = :manager_id");
    $stmt->execute(['id' => $_POST['food_id'], 'manager_id' => $managerId]);
    header("Location: view_foods.php");
    exit;
}
?>

<a href="./add_food.php">Add Food</a>
<table border="1">
    <tr>
        <th>Image</th>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($foods as $food): ?>
        <tr>
            <td><img style="width: 40px; height: 40px; border-radius: 100%" src="<?= $food['image'] ?>" alt=""></td>
            <td><?= $food['id'] ?></td>
            <td><?= $food['name'] ?></td>
            <td><?= $food['description'] ?></td>
            <td><?= $food['price'] ?></td>
            <td>
                <a href="./edit_food.php?id=<?php echo $food['id']?>">Edit</a>
                <form method="POST">
                    <input type="text" name="food_id" value="<?php echo $food['id'] ?>" hidden />
                    <button type="submit" name="delete_food">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require_once '../../includes/footer.php'; ?>
