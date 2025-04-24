<?php
namespace mvc;

require_once 'init.php'; 

use mvc\models\Database;
use mvc\models\UserRepository;
use mvc\models\StudentRepository;
use mvc\requests\Request;
use mvc\requests\Router;
use mvc\controllers\AuthenticationController;
use mvc\controllers\UserController;
use mvc\controllers\StudentController;
use mvc\middlewares\AuthMiddleware;
use mvc\middlewares\RouteMatcher;

// Database connection
$db = new Database('localhost', 'root', 'root', 'UDB');

// Repositories
$userRepository = new UserRepository($db); 
$studentRepository = new StudentRepository($db);

// Request handling
$request = new Request();

// Controllers
$userController = new UserController($userRepository, $request); 
$studentController = new StudentController($studentRepository, $request);
$authController = new AuthenticationController($userRepository); 
$authMiddleware = new AuthMiddleware($authController); 

// Global variable for user controller
global $userController;

// Load routes
$routes = include __DIR__ . '/routes.php';

// Initialize the router
$router = new Router($request, new RouteMatcher());

// Add routes to the router
foreach ($routes as $route) {
    $router->addRoute($route['method'], $route['path'], $route['handler']);
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
header('Content-Type: application/json');
echo $response->getBody();