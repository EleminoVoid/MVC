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
        $token = null;
        // Check Authorization header first
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                $token = $matches[1];
            } else {
                return new Response(401, json_encode([
                    'error' => 'Invalid token format'
                ]), ['Content-Type' => 'application/json']);
            }
        } elseif (isset($_COOKIE['jwt_token'])) {
            // Fallback to cookie for web logins
            $token = $_COOKIE['jwt_token'];
        } else {
            return new Response(401, json_encode([
                'error' => 'No token provided'
            ]), ['Content-Type' => 'application/json']);
        }
        $result = $this->authController->validateToken($token);
        if ($result instanceof Response) {
            return $result;
        }
        return null;
    }
}