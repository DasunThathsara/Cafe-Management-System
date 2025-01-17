<?php
require_once '../config/database.php';

class Reservation {
    private $id;
    private $customer_id;
    private $table_id;
    private $date_time;
    private $status;

    public function __construct($id, $customer_id, $table_id, $date_time, $status) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->table_id = $table_id;
        $this->date_time = $date_time;
        $this->status = $status;
    }

    public static function createReservation($customer_id, $table_id, $date_time) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO reservations (customer_id, table_id, date_time, status) VALUES (:customer_id, :table_id, :date_time, 'pending')");
        $stmt->execute([
            'customer_id' => $customer_id,
            'table_id' => $table_id,
            'date_time' => $date_time
        ]);
    }

    public static function getReservationsByCustomer($customer_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE customer_id = :customer_id");
        $stmt->execute(['customer_id' => $customer_id]);
        $reservations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reservations[] = new self($row['id'], $row['customer_id'], $row['table_id'], $row['date_time'], $row['status']);
        }
        return $reservations;
    }
}
?>
