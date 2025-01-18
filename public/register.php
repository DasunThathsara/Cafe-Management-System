<?php
require_once '../config/database.php';
require_once '../includes/header.php';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        $error = "Username already taken.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, first_name, last_name, email, password, role) 
                               VALUES (:username, :first_name, :last_name, :email, :password, :role)");
        $stmt->execute([
            'username' => $username,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        if ($role == "manager"){
            // Handle image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../assets/images/cafes";  // Target directory
                $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

                // Generate new filename based on cuisine type
                $imageFilename = preg_replace("/[^a-zA-Z0-9]+/", "_", strtolower($_POST['cafe_name']));
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

            $stmt = $pdo->prepare("INSERT INTO cafes (name, address, phone, status, manager_id, image) 
                               VALUES (:name, :address, :phone, :status, :manager_id, :image)");
            $stmt->execute([
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'name' => $_POST['cafe_name'],
                'status' => 'PENDING',
                'manager_id' => $lastInsertedId = $pdo->lastInsertId(),
                'image' => $imagePath
            ]);
        }

        // Redirect to login page
        header("Location: login.php");
        exit;
    }
}
?>

<style>
    h2 {
        text-align: center;
        color: #333;
        padding: 25px 0;
        font-size: 2.2rem;
        background: linear-gradient(to right, #ff8c00, #ff5900);
        color: white;
        margin: 0;
        border-radius: 20px 20px 0 0;
    }

    button {
        background: linear-gradient(to right, #ff8c00, #ff5900);
        border: none;
        color: white;
        padding: 15px 20px;
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
        position: sticky;
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
        <h2>Register</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" style="padding: 30px">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required>
            <br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required>
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>

            <label for="role">Role:</label>
            <select name="role" id="role" required onchange="toggleManagerFields()">
                <option value="customer">Customer</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
            <br>

            <!-- Additional fields for Manager -->
            <div id="managerFields" style="display: none;">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address">
                <br><br>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone">
                <br><br>

                <label for="cafe_name">Cafe Name:</label>
                <input type="text" name="cafe_name" id="cafe_name">
                <br><br>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit">Register</button>
        </form>

        <p style="text-align: center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

<script>
    function toggleManagerFields() {
        const role = document.getElementById('role').value;
        const managerFields = document.getElementById('managerFields');

        if (role === 'manager') {
            managerFields.style.display = 'block';
        } else {
            managerFields.style.display = 'none';
        }
    }
</script>

<?php require_once '../includes/footer.php'; ?>