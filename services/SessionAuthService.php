<?php
namespace mvc\services;

class SessionAuthService {
    public static function isAuthenticated(): bool {
        session_start();
        return isset($_SESSION['user_id']);
    }
    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }
    public static function login($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    }
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}