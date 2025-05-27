<?php
namespace mvc\middlewares;

use mvc\controllers\AuthenticationController;
use mvc\responses\Response;

class AuthMiddleware {
    private $authController;

    public function __construct(AuthenticationController $authController) {
        $this->authController = $authController;
    }

    public function handle($request) {
        $headers = getallheaders();
        
        // Check if Authorization header exists
        if (!isset($headers['Authorization'])) {
            return new Response(401, json_encode([
                'status' => 'error',
                'message' => 'No token provided'
            ]), ['Content-Type' => 'application/json']);
        }

        // Extract token from Bearer header
        $authHeader = $headers['Authorization'];
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return new Response(401, json_encode([
                'status' => 'error',
                'message' => 'Invalid token format'
            ]), ['Content-Type' => 'application/json']);
        }

        $token = $matches[1];
        $result = $this->authController->validateToken($token);

        if ($result instanceof Response) {
            return $result;
        }

        return null;
    }
}