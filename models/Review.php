<?php
require_once '../config/database.php';

class Review {
    private $id;
    private $customer_id;
    private $food_id;
    private $rating;
    private $comment;

    public function __construct($id, $customer_id, $food_id, $rating, $comment) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->food_id = $food_id;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public static function addReview($customer_id, $food_id, $rating, $comment) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO reviews (customer_id, food_id, rating, comment) VALUES (:customer_id, :food_id, :rating, :comment)");
        $stmt->execute([
            'customer_id' => $customer_id,
            'food_id' => $food_id,
            'rating' => $rating,
            'comment' => $comment
        ]);
    }

    public static function getReviewsByFood($food_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE food_id = :food_id");
        $stmt->execute(['food_id' => $food_id]);
        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new self($row['id'], $row['customer_id'], $row['food_id'], $row['rating'], $row['comment']);
        }
        return $reviews;
    }
}
?>
