<?php
require_once '../config/database.php';

class Admin {
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
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($password, $admin['password'])) {
            return new self($admin['id'], $admin['username'], $admin['password']);
        }
        return false;
    }

    public static function register($username, $password) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
?>
