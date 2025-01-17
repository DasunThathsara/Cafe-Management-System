<?php
require_once '../config/database.php';

class Complaint {
    public static function createComplaint($customerId, $cafeId, $complaintText) {
        try {
            $db = Database::connect();
            $query = "INSERT INTO complaints (customer_id, cafe_id, complaint_text, status, created_at) 
                      VALUES (?, ?, ?, 'Pending', NOW())";
            $stmt = $db->prepare($query);
            $stmt->execute([$customerId, $cafeId, $complaintText]);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            error_log('Error creating complaint: ' . $e->getMessage());
            return false;
        }
    }

    public static function getAllComplaints() {
        try {
            $db = Database::connect();
            $query = "SELECT c.id, c.complaint_text, c.status, c.created_at, cu.name AS customer_name, ca.name AS cafe_name 
                      FROM complaints c
                      JOIN customers cu ON c.customer_id = cu.id
                      JOIN cafes ca ON c.cafe_id = ca.id";
            $stmt = $db->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Database::disconnect();
            return $results;
        } catch (PDOException $e) {
            error_log('Error fetching complaints: ' . $e->getMessage());
            return [];
        }
    }

    public static function updateComplaintStatus($complaintId, $status) {
        try {
            $db = Database::connect();
            $query = "UPDATE complaints SET status = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$status, $complaintId]);
            Database::disconnect();
            return true;
        } catch (PDOException $e) {
            error_log('Error updating complaint status: ' . $e->getMessage());
            return false;
        }
    }
}
?>
