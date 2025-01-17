<?php
require_once '../config/database.php';

class Food {
    private $id;
    private $name;
    private $price;
    private $cafe_id;

    public function __construct($id, $name, $price, $cafe_id) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->cafe_id = $cafe_id;
    }

    public static function getAllFoods() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM food");
        $stmt->execute();
        $foods = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $foods[] = new self($row['id'], $row['name'], $row['price'], $row['cafe_id']);
        }
        return $foods;
    }

    public static function getFoodsByCafe($cafe_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM food WHERE cafe_id = :cafe_id");
        $stmt->execute(['cafe_id' => $cafe_id]);
        $foods = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $foods[] = new self($row['id'], $row['name'], $row['price'], $row['cafe_id']);
        }
        return $foods;
    }

    public static function addFood($name, $price, $cafe_id) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO foods (name, price, cafe_id) VALUES (:name, :price, :cafe_id)");
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'cafe_id' => $cafe_id
        ]);
    }

    public static function updateFood($foodId, $name, $price) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE foods SET name = :name, price = :price WHERE id = :food_id");
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'food_id' => $foodId
        ]);
    }
}
?>
