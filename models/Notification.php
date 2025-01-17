<?php
require_once '../config/database.php';

class Notification {
    private $id;
    private $customer_id;
    private $message;
    private $status;

    public function __construct($id, $customer_id, $message, $status) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->message = $message;
        $this->status = $status;
    }

    public static function sendNotification($customer_id, $message) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO notification (customer_id, message, status) VALUES (:customer_id, :message, 'unread')");
        $stmt->execute([
            'customer_id' => $customer_id,
            'message' => $message
        ]);
    }

    public static function getNotificationsByCustomer($customer_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE customer_id = :customer_id");
        $stmt->execute(['customer_id' => $customer_id]);
        $notifications = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notifications[] = new self($row['id'], $row['customer_id'], $row['message'], $row['status']);
        }
        return $notifications;
    }

    public static function markAsRead($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE notifications SET status = 'read' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
