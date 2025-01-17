<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';


if (isset($_GET['cafe_id'])){
    $stmt = $pdo->prepare("SELECT f.*, c.id AS cafe_id FROM foods f LEFT JOIN cafes c ON f.manager_id = c.manager_id WHERE c.id = :cafe_id");
    $stmt->execute(['cafe_id' => $_GET['cafe_id']]);
} else{
    $stmt = $pdo->prepare("SELECT f.*, c.id AS cafe_id FROM foods f LEFT JOIN cafes c ON f.manager_id = c.manager_id");
    $stmt->execute();
}

$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($foods[0]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_food_id']) && isset($_POST['order_manager_id'])) {
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, food_ids, status, manager_id) VALUES (:customer_id, :food_ids, 'PENDING', :manager_id)");
    $stmt->execute([
        'customer_id' => $_SESSION['user_id'],
        'food_ids' => $_POST['order_food_id'],
        'manager_id' => $_POST['order_manager_id']
    ]);
    header("Location: dashboard.php");
    exit;
}
?>
<h2>Menu</h2>
<form method="post">
    <table border="1">
        <tr>
            <th>Food Item</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($foods as $food): ?>
            <tr>
                <td>
                    <div>
                        <img src="<?= $food['image']?>" style="width: 50px; height: 50px; border-radius: 100%" alt="">
                    </div>
                    <div>
                        <p><?= htmlspecialchars($food['name']); ?></p>
                        <p><?= htmlspecialchars($food['description']); ?></p>
                    </div>
                </td>
                <td><?= number_format($food['price'], 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="text" name="order_food_id" value="<?= $food['id']?>" hidden />
                        <input type="text" name="order_manager_id" value="<?= $food['manager_id']?>" hidden />
                        <button type="submit" class="btn-2">Place Order</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit" name="order_food">Place Order</button>
</form>
<?php require_once '../../includes/footer.php'; ?>
