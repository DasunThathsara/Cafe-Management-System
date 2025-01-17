<?php
require_once '../models/Food.php';
require_once '../models/Table.php';
require_once '../models/Order.php';
require_once '../models/Reservation.php';
require_once '../includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Manage foods
    if (isset($_GET['manage_foods'])) {
        $foods = Food::getAllFoods();
        include '../public/manager/view_foods.php';
    }

    // Manage tables
    if (isset($_GET['manage_tables'])) {
        $tables = Table::getAllTables();
        include '../public/manager/view_tables.php';
    }

    // View orders
    if (isset($_GET['view_orders'])) {
        $orders = Order::getAllOrders();
        include '../public/manager/view_orders.php';
    }

    // View reservations
    if (isset($_GET['view_reservations'])) {
        $reservations = Reservation::getAllReservations();
        include '../public/manager/approve_reservations.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add food
    if (isset($_POST['add_food'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        Food::addFood($name, $price);
        $_SESSION['success'] = 'Food added successfully!';
        redirect('../public/manager/view_foods.php');
    }

    // Update food
    if (isset($_POST['update_food'])) {
        $foodId = $_POST['food_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        Food::updateFood($foodId, $name, $price);
        $_SESSION['success'] = 'Food updated successfully!';
        redirect('../public/manager/view_foods.php');
    }

    // Approve reservation
    if (isset($_POST['approve_reservation'])) {
        $reservationId = $_POST['reservation_id'];
        Reservation::approveReservation($reservationId);
        $_SESSION['success'] = 'Reservation approved!';
        redirect('../public/manager/approve_reservations.php');
    }

    // Submit a complaint
    if (isset($_POST['make_complaint'])) {
        $cafeId = $_POST['cafe_id'];
        $customerId = $_SESSION['user_id'];
        $complaint = $_POST['complaint'];

        // Validate inputs
        if (empty($complaint)) {
            $_SESSION['error'] = 'Complaint cannot be empty.';
            redirect('../public/customer/complaints.php');
        }

        // Create complaint
        if (Complaint::createComplaint($customerId, $cafeId, $complaint)) {
            $_SESSION['success'] = 'Complaint submitted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to submit complaint. Please try again.';
        }
        redirect('../public/customer/complaints.php');
    }
}
?>
