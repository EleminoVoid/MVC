<?php
namespace mvc\middlewares;

class SessionAuthMiddleware {
    public function handle($request) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        return null;
    }
}