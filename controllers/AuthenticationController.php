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

        if (empty($data['password'])) {
            ob_start();
            ?>
            <h2>Registration Failed</h2>
            <p>Password is required.</p>
            <a href="/register">Back to Register</a>
            <meta http-equiv="refresh" content="1;url=/register">
            <?php
            $content = ob_get_clean();
            return new Response(400, $content, ['Content-Type' => 'text/html']);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userRepository->create($data);

        // HTML success for form with 1s redirect
        ob_start();
        ?>
        <h2>Registration Successful</h2>
        <p>You can now <a href="/login">login</a>.</p>
        <meta http-equiv="refresh" content="1;url=/login">
        <?php
        $content = ob_get_clean();
        return new Response(201, $content, ['Content-Type' => 'text/html']);
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !is_array($data)) {
            $data = $_POST;
        }

        if (empty($data['email']) || empty($data['password'])) {
            ob_start();
            ?>
            <h2>Login Failed</h2>
            <p>Email and password are required.</p>
            <a href="/login">Back to Login</a>
            <meta http-equiv="refresh" content="1;url=/login">
            <?php
            $content = ob_get_clean();
            return new Response(400, $content, ['Content-Type' => 'text/html']);
        }

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->getByEmail($email);

        if (!$user) {
            ob_start();
            ?>
            <h2>Login Failed</h2>
            <p>Invalid email.</p>
            <a href="/login">Back to Login</a>
            <meta http-equiv="refresh" content="1;url=/login">
            <?php
            $content = ob_get_clean();
            return new Response(401, $content, ['Content-Type' => 'text/html']);
        }

        if (!password_verify($password, $user['password'])) {
            ob_start();
            ?>
            <h2>Login Failed</h2>
            <p>Invalid password.</p>
            <a href="/login">Back to Login</a>
            <meta http-equiv="refresh" content="1;url=/login">
            <?php
            $content = ob_get_clean();
            return new Response(401, $content, ['Content-Type' => 'text/html']);
        }

        // Set session for authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        // On success, show message and redirect to home after 1s
        ob_start();
        ?>
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