<?php
require_once '../models/Cafe.php';
require_once '../models/Food.php';
require_once '../models/Reservation.php';
require_once '../models/Complaint.php';
require_once '../models/Review.php';
require_once '../includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // View all cafes
    if (isset($_GET['view_cafes'])) {
        $cafes = Cafe::getAllCafes();
        include '../public/customer/view_cafes.php';
    }

    // View specific cafe
    if (isset($_GET['view_cafe'])) {
        $cafeId = $_GET['cafe_id'];
        $cafe = Cafe::getCafeById($cafeId);
        $foods = Food::getFoodsByCafe($cafeId);
        include '../public/customer/order_food.php';
    }

    // View all orders
    if (isset($_GET['view_orders'])) {
        $orders = Order::getCustomerOrders($_SESSION['user_id']);
        include '../public/customer/order_food.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Make reservation
    if (isset($_POST['make_reservation'])) {
        $cafeId = $_POST['cafe_id'];
        $customerId = $_SESSION['user_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        Reservation::createReservation($customerId, $cafeId, $date, $time);
        $_SESSION['success'] = 'Reservation made successfully!';
        redirect('../public/customer/dashboard.php');
    }

    // Order food
    if (isset($_POST['order_food'])) {
        $foodIds = $_POST['food_ids'];
        $customerId = $_SESSION['user_id'];
        Order::createOrder($customerId, $foodIds);
        $_SESSION['success'] = 'Order placed successfully!';
        redirect('../public/customer/dashboard.php');
    }

    // Make a complaint
    if (isset($_POST['make_complaint'])) {
        $cafeId = $_POST['cafe_id'];
        $complaintText = trim($_POST['complaint']);
        $customerId = $_SESSION['user_id'];

        // Validate inputs
        if (empty($complaintText)) {
            $_SESSION['error'] = 'Complaint cannot be empty.';
            redirect('../public/customer/complaints.php');
            exit();
        }

        $result = Complaint::createComplaint($customerId, $cafeId, $complaintText);
        if ($result) {
            $_SESSION['success'] = 'Complaint submitted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to submit complaint. Please try again later.';
        }
        redirect('../public/customer/complaints.php');
    }
}
?>
