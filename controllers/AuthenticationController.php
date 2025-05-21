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
        // Support both JSON and form submissions
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            return new Response(400, json_encode(['error' => 'Password is required']), ['Content-Type' => 'application/json']);
        }

        $this->userRepository->create($data);

        // If it's a form submission, show a message and redirect to login
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            ob_start();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Registration Successful</title>
                <meta http-equiv="refresh" content="2;url=/login">
                <link rel="stylesheet" href="styles.css">
            </head>
            <body>
                <main>
                    <h2>Registration successful!</h2>
                    <p>You will be redirected to the login page shortly.</p>
                </main>
            </body>
            </html>
            <?php
            $content = ob_get_clean();
            return new Response(201, $content, ['Content-Type' => 'text/html']);
        }

        // For API/JSON
        return new Response(201, json_encode(['message' => 'User registered successfully']), ['Content-Type' => 'application/json']);
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        if (empty($data['email']) || empty($data['password'])) {
            return new Response(400, json_encode(['error' => 'Email and password are required']), ['Content-Type' => 'application/json']);
        }

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            // Show error message for form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                ob_start();
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Login Failed</title>
                    <meta http-equiv="refresh" content="2;url=/login">
                    <link rel="stylesheet" href="styles.css">
                </head>
                <body>
                    <main>
                        <h2>Login failed!</h2>
                        <p>Invalid email or password. Redirecting back to login...</p>
                    </main>
                </body>
                </html>
                <?php
                $content = ob_get_clean();
                return new Response(401, $content, ['Content-Type' => 'text/html']);
            }
            // For API/JSON
            return new Response(401, json_encode(['error' => 'Invalid email or password']), ['Content-Type' => 'application/json']);
        }

        // On successful login
        $payload = [
            'iss' => 'http://localhost',
            'aud' => 'http://localhost',
            'iat' => time(),
            'exp' => time() + 3600,
            'userId' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name']
        ];

        $jwt = JWT::encode($payload, 'your-secret-key', 'HS256');

        // If it's a form submission, show a message and redirect to homepage
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            ob_start();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Login Successful</title>
                <meta http-equiv="refresh" content="2;url=/home">
                <link rel="stylesheet" href="styles.css">
            </head>
            <body>
                <main>
                    <h2>Login successful!</h2>
                    <p>Welcome, <?php echo htmlspecialchars($user['name']); ?>. Redirecting to homepage...</p>
                </main>
            </body>
            </html>
            <?php
            $content = ob_get_clean();
            setcookie('token', $jwt, time() + 3600, "/"); // site-wide cookie
            return new Response(200, $content, ['Content-Type' => 'text/html']);
        }

        // For API/JSON
        return new Response(200, json_encode(['token' => $jwt]), ['Content-Type' => 'application/json']);
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


}