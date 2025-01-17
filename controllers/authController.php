<?php
require_once '../models/Admin.php';
require_once '../models/CafeManager.php';
require_once '../models/Customer.php';
require_once '../includes/utils.php';

// Handle user login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if admin
    $admin = Admin::authenticate($username, $password);
    if ($admin) {
        $_SESSION['user_id'] = $admin->id;
        $_SESSION['username'] = $admin->username;
        $_SESSION['role'] = 'admin';
        redirect('../public/admin/dashboard.php');
    }

    // Check if cafe manager
    $manager = CafeManager::authenticate($username, $password);
    if ($manager) {
        $_SESSION['user_id'] = $manager->id;
        $_SESSION['username'] = $manager->username;
        $_SESSION['role'] = 'manager';
        redirect('../public/manager/dashboard.php');
    }

    // Check if customer
    $customer = Customer::authenticate($username, $password);
    if ($customer) {
        $_SESSION['user_id'] = $customer->id;
        $_SESSION['username'] = $customer->username;
        $_SESSION['role'] = 'customer';
        redirect('../public/customer/dashboard.php');
    }

    // If authentication failed
    $_SESSION['error'] = 'Invalid credentials.';
    redirect('../public/login.php');
}

// Handle user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role === 'admin') {
        Admin::register($username, $password);
    } elseif ($role === 'manager') {
        CafeManager::register($username, $password);
    } else {
        Customer::register($username, $password);
    }

    $_SESSION['success'] = 'Registration successful!';
    redirect('../public/login.php');
}
?>
