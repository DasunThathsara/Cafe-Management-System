<?php
require_once '../config/database.php';

class CafeManager {
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
        $stmt = $pdo->prepare("SELECT * FROM cafe_managers WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($manager && password_verify($password, $manager['password'])) {
            return new self($manager['id'], $manager['username'], $manager['password']);
        }
        return false;
    }

    public static function register($username, $password) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO cafe_managers (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
?>
