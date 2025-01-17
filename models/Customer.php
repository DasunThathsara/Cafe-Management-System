<?php
require_once '../config/database.php';

class Customer {
    private $id;
    private $username;
    private $password;

    public function __construct($id, $username, $password) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function authenticate($username, $password) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($customer && password_verify($password, $customer['password'])) {
            return new self($customer['id'], $customer['username'], $customer['password']);
        }
        return false;
    }

    public static function register($username, $password) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO customers (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
?>
