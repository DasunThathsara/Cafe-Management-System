<?php
session_start();
require_once '../config/database.php';
require_once '../includes/header.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the appropriate dashboard
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php");
                break;
            case 'manager':
                header("Location: manager/dashboard.php");
                break;
            case 'customer':
                header("Location: customer/dashboard.php");
                break;
            default:
                header("Location: index.php");
        }
        exit;
    } else {
        $error = "Invalid username or password.";
    }
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
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" style="padding: 30px">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit" class="btn-2">Login</button>
        </form>

        <p style="text-align: center">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>