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
<h1>Login</h1>
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
