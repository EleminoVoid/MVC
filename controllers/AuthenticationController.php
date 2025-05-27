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

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !is_array($data)) {
            $data = $_POST;
        }

        if (empty($data['email']) || empty($data['name']) || empty($data['password'])) {
            ob_start();
            ?>
            <h2>Registration Failed</h2>
            <p>Email, name, and password are required.</p>
            <a href="/register">Back to Register</a>
            <meta http-equiv="refresh" content="1;url=/register">
            <?php
            $content = ob_get_clean();
            return new Response(400, $content, ['Content-Type' => 'text/html']);
        }

        // Check if email already exists
        if ($this->userRepository->getByEmail($data['email'])) {
            ob_start();
            ?>
            <h2>Registration Failed</h2>
            <p>Email is already registered.</p>
            <a href="/register">Back to Register</a>
            <meta http-equiv="refresh" content="1;url=/register">
            <?php
            $content = ob_get_clean();
            return new Response(409, $content, ['Content-Type' => 'text/html']);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userRepository->create($data);

        // HTML success for form with 1s redirect
        ob_start();
        ?>
        <link rel="stylesheet" href="/styles.css">
        <h2>Registration Successful</h2>
        <p>You can now <a href="/login">login</a>.</p>
        <meta http-equiv="refresh" content="1;url=/login">
        <?php
        $content = ob_get_clean();
        return new Response(201, $content, ['Content-Type' => 'text/html']);
    }

    public function login() {
        // Get request data
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Better API detection - check both Content-Type and Accept headers
        $isApi = (
            isset($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
        ) || (
            isset($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
        );

        // For non-JSON requests, fallback to POST data
        if (!$data || !is_array($data)) {
            $data = $_POST;
        }

        // Validate input
        if (empty($data['email']) || empty($data['password'])) {
            if ($isApi) {
                return new Response(400, json_encode([
                    'status' => 'error',
                    'message' => 'Email and password are required'
                ]), ['Content-Type' => 'application/json']);
            }
            ob_start();
            ?>
            <link rel="stylesheet" href="/styles.css">
            <h2>Login Failed</h2>
            <p>Email and password are required.</p>
            <a href="/login">Back to Login</a>
            <meta http-equiv="refresh" content="1;url=/login">
            <?php
            $content = ob_get_clean();
            return new Response(400, $content, ['Content-Type' => 'text/html']);
        }

        // Verify credentials
        $user = $this->userRepository->getByEmail($data['email']);
        
        if (!$user || !password_verify($data['password'], $user['password'])) {
            if ($isApi) {
                return new Response(401, json_encode([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ]), ['Content-Type' => 'application/json']);
            }
            ob_start();
            ?>
            <link rel="stylesheet" href="/styles.css">
            <h2>Login Failed</h2>
            <p>Invalid email or password.</p>
            <a href="/login">Back to Login</a>
            <meta http-equiv="refresh" content="1;url=/login">
            <?php
            $content = ob_get_clean();
            return new Response(401, $content, ['Content-Type' => 'text/html']);
        }

        // Handle API login
        if ($isApi) {
            $payload = [
                'iss' => 'http://localhost',
                'aud' => 'http://localhost',
                'iat' => time(),
                'exp' => time() + 3600,
                'userId' => $user['id'],
                'email' => $user['email']
            ];
            $jwt = JWT::encode($payload, 'your-secret-key', 'HS256');
            return new Response(200, json_encode([
                'status' => 'success',
                'token' => $jwt,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name']
                ]
            ]), ['Content-Type' => 'application/json']);
        }

        // Session for browser
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        // On success, show message and redirect to home after 1s
        ob_start();
        ?>
        <link rel="stylesheet" href="/styles.css">
        <h2>Login Successful</h2>
        <p>Welcome back! Redirecting to homepage...</p>
        <meta http-equiv="refresh" content="1;url=/home">
        <?php
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key('your-secret-key', 'HS256'));
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return new Response(401, json_encode(['error' => 'Token has expired']), ['Content-Type' => 'application/json']);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return new Response(401, json_encode(['error' => 'Invalid token signature']), ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return new Response(401, json_encode(['error' => 'Invalid token']), ['Content-Type' => 'application/json']);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}