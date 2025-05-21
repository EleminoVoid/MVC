<?php
namespace mvc\middlewares;

use mvc\controllers\AuthenticationController;

class AuthMiddleware {
    private $authController;

    public function __construct(AuthenticationController $authController) {
        $this->authController = $authController;
    }

    public function handle($request) {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $decoded = $this->authController->validateToken($token);
            if (is_array($decoded) && isset($decoded['error'])) {
                header('Location: /login');
                exit;
            }
            return null;
        }
        header('Location: /login');
        exit;
    }
}