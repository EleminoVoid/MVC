<?php
namespace mvc;

use mvc\controllers\ViewController;
use mvc\controllers\AuthenticationController;
use mvc\middlewares\SessionAuthMiddleware;
use mvc\models\StudentRepository;
use mvc\responses\Response;

global $request, $controller, $studentController, $userController, $authController, $authMiddleware;

$viewController = new ViewController();
$authController = new AuthenticationController($userRepository);
$authMiddleware = new SessionAuthMiddleware();
$studentRepository = new StudentRepository($db);
return [
    // Public views
    [
        'method' => 'GET',
        'path' => '/login',
        'handler' => fn() => $viewController->showLogin()
    ],
    [
        'method' => 'GET',
        'path' => '/register',
        'handler' => fn() => $viewController->showRegister()
    ],
    // Auth actions (API only)
    [
        'method' => 'POST',
        'path' => '/api/login',
        'handler' => fn() => $authController->login()
    ],
    [
        'method' => 'POST',
        'path' => '/api/register',
        'handler' => fn() => $authController->register()
    ],
    [
        'method' => 'GET',
        'path' => '/logout',
        'handler' => fn() => $authController->logout()
    ],
    // Private views (protected by middleware)
    [
        'method' => 'GET',
        'path' => '/home',
        'handler' => function() use ($authMiddleware, $request, $viewController) {
            $authMiddleware->handle($request);
            return $viewController->showHome();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students',
        'handler' => function() use ($authMiddleware, $request, $viewController, $studentRepository) {
            $authMiddleware->handle($request);
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            return $viewController->showStudentList($studentRepository, $page);
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/{id}/edit',
        'handler' => function($id) use ($authMiddleware, $request, $viewController, $studentRepository) {
            $authMiddleware->handle($request);
            return $viewController->showStudentEdit($id, $studentRepository);
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/{id}/delete',
        'handler' => function($id) use ($authMiddleware, $request, $viewController, $studentRepository) {
            $authMiddleware->handle($request);
            return $viewController->showStudentDelete($id, $studentRepository);
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/create',
        'handler' => function() use ($authMiddleware, $request, $viewController) {
            $authMiddleware->handle($request);
            return $viewController->showStudentCreate();
        }
    ],
    // API routes (all POST/PUT/DELETE)
    [
        'method' => 'POST',
        'path' => '/api/students',
        'handler' => function() use ($authMiddleware, $request, $studentController) {
            $authMiddleware->handle($request);
            return $studentController->createStudent();
        }
    ],
    [
        'method' => 'POST',
        'path' => '/api/students/{id}',
        'handler' => function($id) use ($authMiddleware, $request, $studentController) {
            $authMiddleware->handle($request);
            $method = $_POST['_method'] ?? '';
            if ($method === 'PUT') {
                return $studentController->updateStudent($id);
            } elseif ($method === 'DELETE') {
                return $studentController->deleteStudent($id);
            }
            return new Response(400, 'Invalid method');
        }
    ],
    // ... more API routes ...
];