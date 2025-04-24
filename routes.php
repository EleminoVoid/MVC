<?php
namespace mvc;

global $request, $controller, $studentController, $userController, $authController, $authMiddleware;

return [
    // Users
    [
        'method' => 'GET',
        'path' => '/users',
        'handler' => function () use ($userController, $authMiddleware, $request) {
            echo "Users route matched\n";
            $authResponse = $authMiddleware->handle($request);
            if ($authResponse) return $authResponse;

            return $userController->getAllUsers();
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
            echo "Login route matched\n";
            return $authController->login();
        }
    ],
];