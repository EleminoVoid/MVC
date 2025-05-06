<?php
// File: /mvc/middlewares/AuthMiddleware.php
namespace mvc\middlewares;

use mvc\controllers\AuthenticationController;

class AuthMiddleware {
    private $authController;

    public function __construct(AuthenticationController $authController) {
        $this->authController = $authController;
    }

    public function handle($request) {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return $this->unauthorizedResponse();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = $this->authController->validateToken($token);

        if (is_array($decoded) && isset($decoded['error'])) {
            return $decoded; 
        }

        return null;
    }

    private function unauthorizedResponse() {
        return ['status' => 401, 'error' => 'Unauthorized'];
    }
}