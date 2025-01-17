<?php
require_once '../config/database.php';

class Cafe {
    private $id;
    private $name;
    private $address;
    private $status;

    public function __construct($id, $name, $address, $status) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->status = $status;
    }

    public static function getAllPendingCafes() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM cafes WHERE status = 'pending'");
        $stmt->execute();
        $cafes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cafes[] = new self($row['id'], $row['name'], $row['address'], $row['status']);
        }
        return $cafes;
    }

    public static function approveCafe($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'approved' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public static function rejectCafe($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'rejected' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
