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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_food'])) {
        $name = $_POST['name'];
        $cuisine_type = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = "../../assets/images/foods";  // Target directory
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            // Generate new filename based on cuisine type
            $imageFilename = preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($cuisine_type));
            $imagePath = $targetDir . $imageFilename . '.' . $imageFileType;

            // Check if the file is an actual image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                die("File is not an image.");
            }

            // Ensure unique filename
            while (file_exists($imagePath)) {
                $imagePath = $targetDir . $imageFilename . '_' . uniqid() . '.' . $imageFileType;
            }

            // Check file size (limit to 50MB)
            if ($_FILES["image"]["size"] > 50000000) {
                die("Sorry, your file is too large.");
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Try to upload the file
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                die("Sorry, there was an error uploading your file.");
            }
        }

        $stmt = $pdo->prepare("INSERT INTO foods (name, price, manager_id, image, description, category) VALUES (:name, :price, :manager_id, :image, :description, :category)");
        $stmt->execute([
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'image' => $imagePath,
            'manager_id' => $_SESSION['user_id'],
            'description' => $_POST['description'],
            'category' => $_POST['category']
        ]);
    }
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
        <h2>Add New Meal</h2>
        <form method="POST" enctype="multipart/form-data" style="padding: 30px">
            <input type="text" name="name" placeholder="Food Name" required>
            <input type="text" name="description" placeholder="Description" required>

            <select name="category" required>
                <option value="" disabled selected hidden>Select category</option>
                <option value="food">Meals</option>
                <option value="beverage">Beverage</option>
            </select>

            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <button type="submit" class="btn-2" name="add_food" >Add Food</button>
        </form>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
