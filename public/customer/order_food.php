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
<style>
    h2 {
        text-align: center;
        color: #ff8c00;
        margin: 20px 0;
        font-size: 2rem;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ffc966;
    }

    th {
        background-color: #ff9900;
        color: white;
        font-weight: bold;
    }

    td {
        background-color: #ffebcc;
        color: #555;
    }

    tr:hover {
        background-color: #ffe0b3;
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

<h2>Menu</h2>
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
<?php require_once '../../includes/footer.php'; ?>
