<?php
require_once '../models/Cafe.php';
require_once '../models/Complaint.php';
require_once '../includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // View all café registration requests
    if (isset($_GET['view_cafes'])) {
        $cafes = Cafe::getAllPendingCafes();
        include '../public/admin/manage_cafes.php';
    }

    // View all complaints
    if (isset($_GET['view_complaints'])) {
        $complaints = Complaint::getAllComplaints();
        include '../public/admin/view_complaints.php';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Approve or reject café registration
    if (isset($_POST['approve_cafe'])) {
        $cafeId = $_POST['cafe_id'];
        Cafe::approveCafe($cafeId);
        $_SESSION['success'] = 'Café approved!';
        redirect('../public/admin/manage_cafes.php');
    }

    if (isset($_POST['reject_cafe'])) {
        $cafeId = $_POST['cafe_id'];
        Cafe::rejectCafe($cafeId);
        $_SESSION['success'] = 'Café rejected!';
        redirect('../public/admin/manage_cafes.php');
    }

    // Update complaint status
    if (isset($_POST['update_complaint_status'])) {
        $complaintId = $_POST['complaint_id'];
        $status = $_POST['status'];

        $result = Complaint::updateComplaintStatus($complaintId, $status);
        if ($result) {
            $_SESSION['success'] = 'Complaint status updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update complaint status.';
        }
        redirect('../public/admin/view_complaints.php');
    }
}
?>
