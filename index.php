<?php
// File: /mvc/index.php
namespace mvc;

require_once 'init.php'; // Include the initialization file

use mvc\models\Database;
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
$authController = new AuthenticationController($userRepository, $request); 
$authMiddleware = new AuthMiddleware($authController); 
$viewController = new ViewController($userRepository, $request); 

// Global variable for user controller
global $userController;

// Load routes
$allRoutes = include __DIR__ . '/routes.php';

// Initialize the router
$router = new Router($request, new RouteMatcher());

// Loop through grouped routes and register each
foreach ($allRoutes as $group) {
    foreach ($group['routes'] as $route) {
        $router->addRoute($route['method'], $route['path'], $route['handler']);
    }
}

// Dispatch the request
$response = $router->dispatch();

// Handle array or Response object
if (is_array($response)) {
    http_response_code($response['status'] ?? 200);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} elseif ($response instanceof \mvc\responses\Response) {
    http_response_code($response->getStatusCode());
    header('Content-Type: application/json');
    echo $response->getBody();
    exit;
} else {
    // fallback
    http_response_code(200);
    echo $response;
    exit;
}
