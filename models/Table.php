<?php
require_once '../config/database.php';

class Table {
    private $id;
    private $number;
    private $seats;
    private $status;

    public function __construct($id, $number, $seats, $status) {
        $this->id = $id;
        $this->number = $number;
        $this->seats = $seats;
        $this->status = $status;
    }

    public static function getAllTables() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM tables");
        $stmt->execute();
        $tables = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = new self($row['id'], $row['number'], $row['seats'], $row['status']);
        }
        return $tables;
    }
}
?>
