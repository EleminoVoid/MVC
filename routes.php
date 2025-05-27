<?php
namespace mvc;

use mvc\controllers\ViewController;
use mvc\controllers\AuthenticationController;
use mvc\middlewares\SessionAuthMiddleware;
use mvc\middlewares\AuthMiddleware;
use mvc\models\StudentRepository;
use mvc\responses\Response;

global $request, $controller, $studentController, $userController, $authController, $authMiddleware;

$viewController = new ViewController();
$authController = new AuthenticationController($userRepository);
$jwtMiddleware = new AuthMiddleware($authController);
$studentRepository = new StudentRepository($db);
$sessionAuth = new SessionAuthMiddleware();
return [
    // Web views (session auth)
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
    [
        'method' => 'GET',
        'path' => '/home',
        'handler' => function() use ($sessionAuth, $request, $viewController) {
            $sessionAuth->handle($request);
            return $viewController->showHome();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students',
        'handler' => function() use ($sessionAuth, $request, $viewController, $studentRepository) {
            $sessionAuth->handle($request);
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            return $viewController->showStudentList($studentRepository, $page);
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/create',
        'handler' => function() use ($sessionAuth, $request, $viewController) {
            $sessionAuth->handle($request);
            return $viewController->showStudentCreate();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/{id}/edit',
        'handler' => function($id) use ($sessionAuth, $request, $viewController, $studentRepository) {
            $sessionAuth->handle($request);
            return $viewController->showStudentEdit($id, $studentRepository);
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/{id}/delete',
        'handler' => function($id) use ($sessionAuth, $request, $viewController, $studentRepository) {
            $sessionAuth->handle($request);
            return $viewController->showStudentDelete($id, $studentRepository);
        }
    ],
    // API Auth
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
    // API Students CRUD (JWT protected)
    [
        'method' => 'GET',
        'path' => '/api/students',
        'handler' => function() use ($jwtMiddleware, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->getAllStudents();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/api/students/{id}',
        'handler' => function($id) use ($jwtMiddleware, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->getStudentById($id);
        }
    ],
    [
        'method' => 'POST',
        'path' => '/api/students',
        'handler' => function() use ($jwtMiddleware, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->createStudent();
        }
    ],
    [
        'method' => 'PUT',
        'path' => '/api/students/{id}',
        'handler' => function($id) use ($jwtMiddleware, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->updateStudent($id);
        }
    ],
    [
        'method' => 'DELETE',
        'path' => '/api/students/{id}',
        'handler' => function($id) use ($jwtMiddleware, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->deleteStudent($id);
        }
    ],
    [
        'method' => 'POST',
        'path' => '/students/{id}/edit',
        'handler' => function($id) use ($jwtMiddleware, $sessionAuth, $request, $studentController) {
            $middlewareResponse = $jwtMiddleware->handle($request);
            if ($middlewareResponse instanceof Response) return $middlewareResponse;
            return $studentController->updateStudent($id);
        }
    ],
];