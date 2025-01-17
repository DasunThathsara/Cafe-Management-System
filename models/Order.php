<?php
require_once '../config/database.php';

class Order {
    private $id;
    private $customer_id;
    private $food_ids;
    private $status;

    public function __construct($id, $customer_id, $food_ids, $status) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->food_ids = $food_ids;
        $this->status = $status;
    }

    public static function getAllOrders() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM order");
        $stmt->execute();
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = new self($row['id'], $row['customer_id'], $row['food_ids'], $row['status']);
        }
        return $orders;
    }

    public static function createOrder($customer_id, $food_ids) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, food_ids, status) VALUES (:customer_id, :food_ids, 'pending')");
        $stmt->execute([
            'customer_id' => $customer_id,
            'food_ids' => json_encode($food_ids)
        ]);
    }

    public static function updateOrderStatus($order_id, $status) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :order_id");
        $stmt->execute([
            'status' => $status,
            'order_id' => $order_id
        ]);
    }
}
?>
