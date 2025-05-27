<?php
namespace mvc\middlewares;
use mvc\services\SessionAuthService;

class SessionAuthMiddleware {
    public function handle($request) {
        if (!SessionAuthService::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
        return null;
    }
}