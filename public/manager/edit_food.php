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

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = :id");
$stmt->execute(['id' => $id]);
$food = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE foods SET name = :name, description = :description, price = :price WHERE id = :id");
    $stmt->execute([
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'description' => $_POST['description'],
        'id' => $_GET['id']
    ]);
    header("Location: view_foods.php");
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
        <h2>Edit Meal</h2>
        <form method="post" style="padding: 30px" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Food Name" required value="<?php echo $food['name']?>">
            <input type="text" name="description" placeholder="Description" required value="<?php echo $food['description']?>">

            <select name="category" required>
                <?php if(isset($_POST)) {?>
                    <option value="" disabled selected hidden>Select category</option>
                <?php }?>
                <?php if($food['category'] == "food") {?>
                    <option selected value="food">Meals</option>
                    <option value="beverage">Beverage</option>
                <?php }?>
                <?php if($food['category'] == "beverage") {?>
                    <option value="food">Meals</option>
                    <option selected value="beverage">Beverage</option>
                <?php }?>
            </select>

            <input type="number" name="price" placeholder="Price" step="0.01" required value="<?php echo $food['price']?>">
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <button type="submit" name="add_food" class="btn-2">Update Food</button>
        </form>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
