<?php
namespace mvc\controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use mvc\models\UserRepository;

class AuthenticationController {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            return ['status' => 400, 'error' => 'Password is required'];
        }

        $this->userRepository->create($data);
        
        return ['status' => 201, 'message' => 'User  registered successfully'];
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            return ['status' => 400, 'error' => 'Email and password are required'];
        }

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->getByEmail($email);

        if (!$user) {
            error_log("User  not found for email: $email");
            return ['status' => 401, 'error' => 'Invalid email'];
        }

        if (!password_verify($password, $user['password'])) {
            error_log("Password mismatch for email: $email");
            return ['status' => 401, 'error' => 'Invalid password'];
        }

        $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost',
            'iat' => time(),
            'exp' => time() + 3600, // 1hr
            'userId' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name']
        ];

        $jwt = JWT::encode($payload, 'your-secret-key', 'HS256');

        return ['status' => 200, 'token' => $jwt];
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key('your-secret-key', 'HS256'));
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return ['status' => 401, 'error' => 'Token has expired'];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return ['status' => 401, 'error' => 'Invalid token signature'];
        } catch (\Exception $e) {
            return ['status' => 401, 'error' => 'Invalid token'];
        }
    }
}