<?php
// File: /mvc/routes.php
namespace mvc;

global $request, $controller, $studentController, $userController, $authController, $authMiddleware, $viewController;

return [
    // Users
    [
        'method' => 'GET',
        'path' => '/users',
        'handler' => function () use ($controller, $authMiddleware, $request) {
            echo "Users route matched\n";
            $authResponse = $authMiddleware->handle($request);
            if ($authResponse) return $authResponse;

            return $controller->getAllUsers();
        }
    ],
    [
        'method' => 'POST',
        'path' => '/users',
        'handler' => function () use ($userController) {
            echo "Create user route matched\n";
            return $userController->createUser ();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/users/{id}',
        'handler' => function ($id) use ($userController) {
            echo "Get user by ID route matched\n";
            return $userController->getUserById($id);
        }
    ],
    [
        'method' => 'PUT',
        'path' => '/users/{id}',
        'handler' => function ($id) use ($userController) {
            echo "Update user route matched\n";
            return $userController->updateUser ($id);
        }
    ],
    [
        'method' => 'DELETE',
        'path' => '/users/{id}',
        'handler' => function ($id) use ($userController) {
            echo "Delete user route matched\n";
            return $userController->deleteUser ($id);
        }
    ],

    // Students (old hah)
    [
        'method' => 'GET',
        'path' => '/students',
        'handler' => function () use ($studentController) {
            echo "Students route matched\n";
            return $studentController->getAllStudents();
        }
    ],
    [
        'method' => 'POST',
        'path' => '/students',
        'handler' => function () use ($studentController) {
            echo "Create student route matched\n";
            return $studentController->createStudent();
        }
    ],
    [
        'method' => 'GET',
        'path' => '/students/{id}',
        'handler' => function ($id) use ($studentController) {
            echo "Get student by ID route matched\n";
            return $studentController->getStudentById($id);
        }
    ],
    [
        'method' => 'PUT',
        'path' => '/students/{id}',
        'handler' => function ($id) use ($studentController) {
            echo "Update student route matched\n";
            return $studentController->updateStudent($id);
        }
    ],
    [
        'method' => 'DELETE',
        'path' => '/students/{id}',
        'handler' => function ($id) use ($studentController) {
            echo "Delete student route matched\n";
            return $studentController->deleteStudent($id);
        }
    ],

    // For JWT

    [
        'method' => 'POST',
        'path' => '/register',
        'handler' => function () use ($authController) {
            echo "Register route matched\n";
            return $authController->register();
        }
    ],

    [
        'method' => 'POST',
        'path' => '/login',
        'handler' => function () use ($authController) {
            $response = $authController->login();
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    ],
    

    //Views
    [
        //??????
        'method' => 'GET',
        'path' => '/home',
        'handler' => function () use ($viewController) {
            $response = $viewController->renderView('home');
            if (isset($response['html'])) {
                echo $response['html'];
            } else {
                http_response_code($response['status']);
                echo json_encode($response);
            }
        }
    ],
    [
        'method' => 'GET',
        'path' => '/login',
        'handler' => function () use ($viewController) {
            $response = $viewController->renderView('login');
            if (isset($response['html'])) {
                echo $response['html'];
            } else {
                http_response_code($response['status']);
                echo json_encode($response);
            }
        }
    ],
    [
        'method' => 'GET',
        'path' => '/register',
        'handler' => function () use ($viewController) {
            $response = $viewController->renderView('register');
            if (isset($response['html'])) {
                echo $response['html'];
            } else {
                http_response_code($response['status']);
                echo json_encode($response);
            }
        }
    ],
    [
        'method' => 'GET',
        'path' => '/services',
        'handler' => function () use ($viewController) {
            $response = $viewController->renderView('services');
            if (isset($response['html'])) {
                echo $response['html'];
            } else {
                http_response_code($response['status']);
                echo json_encode($response);
            }
        }
    ],
    [
        //??????
        'method' => 'GET',
        'path' => '/about',
        'handler' => function () use ($viewController) {
            $response = $viewController->renderView('about');
            if (isset($response['html'])) {
                echo $response['html'];
            } else {
                http_response_code($response['status']);
                echo json_encode($response);
            }
        }
    ],
];