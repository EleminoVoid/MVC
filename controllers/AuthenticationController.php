<?php
namespace mvc\controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use mvc\models\UserRepository;
use mvc\responses\Response;

class AuthenticationController {
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    // POST /api/register
    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        $isApi = false;
        if ((isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            $isApi = true;
        }
        if (!$data || !is_array($data)) {
            $data = $_POST;
        }
        if (empty($data['email']) || empty($data['name']) || empty($data['password'])) {
            if ($isApi) {
                return new Response(400, json_encode(['error' => 'Email, name, and password are required']), ['Content-Type' => 'application/json']);
            } else {
                ob_start();
                echo '<link rel="stylesheet" href="/styles.css">';
                echo '<h2>Registration Failed</h2><p>Email, name, and password are required.</p>';
                echo '<script>setTimeout(function() { window.location.href = "/register"; }, 1000);</script>';
                $content = ob_get_clean();
                return new Response(400, $content, ['Content-Type' => 'text/html']);
            }
        }
        if ($this->userRepository->getByEmail($data['email'])) {
            if ($isApi) {
                return new Response(409, json_encode(['error' => 'Email is already registered']), ['Content-Type' => 'application/json']);
            } else {
                ob_start();
                echo '<link rel="stylesheet" href="/styles.css">';
                echo '<h2>Registration Failed</h2><p>Email is already registered.</p>';
                echo '<script>setTimeout(function() { window.location.href = "/register"; }, 1000);</script>';
                $content = ob_get_clean();
                return new Response(409, $content, ['Content-Type' => 'text/html']);
            }
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userRepository->create($data);
        if ($isApi) {
            return new Response(201, json_encode(['message' => 'Registration successful']), ['Content-Type' => 'application/json']);
        } else {
            ob_start();
            echo '<link rel="stylesheet" href="/styles.css">';
            echo '<h2>Registration Successful</h2><p>You will be redirected to login page.</p>';
            echo '<script>setTimeout(function() { window.location.href = "/login"; }, 1000);</script>';
            $content = ob_get_clean();
            return new Response(201, $content, ['Content-Type' => 'text/html']);
        }
    }

    // POST /api/login
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        $isApi = false;
        // Detect API request by Content-Type or Accept header
        if ((isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            $isApi = true;
        }
        if (!$data || !is_array($data)) {
            $data = $_POST;
        }
        if (empty($data['email']) || empty($data['password'])) {
            if ($isApi) {
                return new Response(400, json_encode(['error' => 'Email and password required']), ['Content-Type' => 'application/json']);
            } else {
                ob_start();
                echo '<link rel="stylesheet" href="/styles.css">';
                echo '<h2>Login Failed</h2><p>Email and password required.</p>';
                echo '<script>setTimeout(function() { window.location.href = "/login"; }, 1000);</script>';
                $content = ob_get_clean();
                return new Response(400, $content, ['Content-Type' => 'text/html']);
            }
        }
        $user = $this->userRepository->getByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            if ($isApi) {
                return new Response(401, json_encode(['error' => 'Invalid credentials']), ['Content-Type' => 'application/json']);
            } else {
                ob_start();
                echo '<link rel="stylesheet" href="/styles.css">';
                echo '<h2>Login Failed</h2><p>Invalid credentials.</p>';
                echo '<script>setTimeout(function() { window.location.href = "/login"; }, 1000);</script>';
                $content = ob_get_clean();
                return new Response(401, $content, ['Content-Type' => 'text/html']);
            }
        }
        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + 3600
        ];
        $secret = $_ENV['JWT_SECRET'] ?? 'secret';
        $jwt = JWT::encode($payload, $secret, 'HS256');
        setcookie('jwt_token', $jwt, time() + 3600, '/', '', false, true); 
        if ($isApi) {
            return new Response(200, json_encode(['token' => $jwt]), ['Content-Type' => 'application/json']);
        } else {
            // Session login for web
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user['id'];
            ob_start();
            echo '<link rel="stylesheet" href="/styles.css">';
            echo '<h2>Login Successful</h2><p>You will be redirected to home page.</p>';
            echo '<script>setTimeout(function() { window.location.href = "/home"; }, 1000);</script>';
            $content = ob_get_clean();
            return new Response(200, $content, ['Content-Type' => 'text/html']);
        }
    }

    // JWT validation for middleware
    public function validateToken($token) {
        $secret = $_ENV['JWT_SECRET'] ?? 'secret';
        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return new Response(401, json_encode(['error' => 'Token has expired']), ['Content-Type' => 'application/json']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return new Response(401, json_encode(['error' => 'Invalid token signature']), ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return new Response(401, json_encode(['error' => 'Invalid token']), ['Content-Type' => 'application/json']);
        }
    }

    // GET /logout
    public function logout() {
        $isApi = (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        // Remove JWT cookie if set
        setcookie('jwt_token', '', time() - 3600, '/');
        if ($isApi) {
            return new Response(200, json_encode(['message' => 'Logged out']), ['Content-Type' => 'application/json']);
        } else {
            ob_start();
            echo '<link rel="stylesheet" href="/styles.css">';
            echo '<h2>Logged Out</h2><p>You will be redirected to login page.</p>';
            echo '<script>setTimeout(function() { window.location.href = "/login"; }, 1000);</script>';
            $content = ob_get_clean();
            return new Response(200, $content, ['Content-Type' => 'text/html']);
        }
    }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   