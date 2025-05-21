<?php
namespace mvc;

require_once 'init.php'; 

use mvc\models\Database;//defunct
use mvc\models\UserRepository;
use mvc\models\StudentRepository;
use mvc\requests\Request;
use mvc\requests\Router;
use mvc\controllers\AuthenticationController;
use mvc\controllers\UserController;
use mvc\controllers\StudentController;
use mvc\controllers\ViewController;
use mvc\middlewares\AuthMiddleware;
use mvc\middlewares\RouteMatcher;
use mvc\models\DBORM;

$dborm = new DBORM('localhost', 'root', 'root', 'UDB');

$userRepository = new UserRepository($dborm); 
$studentRepository = new StudentRepository($dborm);

// Request handling
$request = new Request();

// Controllers
$userController = new UserController($userRepository, $request); 
$studentController = new StudentController($studentRepository, $request);
$authController = new AuthenticationController($userRepository); 
$viewController = new ViewController($studentRepository, $request);
$authMiddleware = new AuthMiddleware($authController); 

// Global variable for user controller
global $userController;

// Load routes
$routes = include __DIR__ . '/routes.php';

// Initialize the router
$router = new Router($request, new RouteMatcher());

// Add routes to the router
foreach ($routes as $group) {
    if (!isset($group['routes']) || !is_array($group['routes'])) continue;
    foreach ($group['routes'] as $route) {
        if (
            isset($route['method'], $route['path'], $route['handler'])
        ) {
            $router->addRoute($route['method'], $route['path'], $route['handler']);
        }
    }
}

// Dispatch the request
$response = $router->dispatch();

// Handle array responses
if (is_array($response)) {
    http_response_code($response['status'] ?? 200);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Send the response
http_response_code($response->getStatusCode());

if (method_exists($response, 'getHeaders')) {
    $headers = $response->getHeaders();
    if (is_array($headers)) {
        foreach ($headers as $name => $value) {
            header("$name: $value");
        }
    } else {
        header('Content-Type: application/json');
    }
}

echo $response->getBody();