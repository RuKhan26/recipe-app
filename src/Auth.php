<?php
require_once 'db.php';

class Auth {
    public static function register($username, $password) {
        global $pdo;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public static function login($username, $password) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }

        return false;
    }

    public static function logout() {
        session_start();
        session_destroy();
    }
}
