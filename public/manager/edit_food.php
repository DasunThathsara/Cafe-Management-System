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
<h2>Edit Foods</h2>
<form method="post" enctype="multipart/form-data">
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
<?php require_once '../../includes/footer.php'; ?>
