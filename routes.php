<?php
namespace mvc;

global $request, $controller, $studentController, $userController, $authController, $authMiddleware, $adminMiddleware;

return [
    // Public routes (no authentication required)
    'public' => [
        'routes' => [
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
            ]
        ]
    ],

    // User CRUD routes
    'users' => [
        'middleware' => [$authMiddleware],
        'routes' => [
            [
                'method' => 'GET',
                'path' => '/users',
                'handler' => function () use ($userController, $adminMiddleware, $request) {
                    echo "List users route matched\n";
                    $adminCheck = $adminMiddleware->handle($request);
                    if ($adminCheck) return $adminCheck;
                    return $userController->getAllUsers();
                }
            ],
            
            [
                'method' => 'POST',
                'path' => '/users',
                'handler' => function () use ($userController) {
                    echo "Create user route matched\n";
                    return $userController->createUser();
                }
            ],
            
            [
                'method' => 'GET',
                'path' => '/users/{id}',
                'handler' => function ($id) use ($userController) {
                    echo "Get user route matched\n";
                    return $userController->getUserById($id);
                }
            ],
            
            [
                'method' => 'PUT',
                'path' => '/users/{id}',
                'handler' => function ($id) use ($userController) {
                    echo "Update user route matched\n";
                    return $userController->updateUser($id);
                }
            ],
            
            [
                'method' => 'DELETE',
                'path' => '/users/{id}',
                'handler' => function ($id) use ($userController, $adminMiddleware, $request) {
                    echo "Delete user route matched\n";
                    $adminCheck = $adminMiddleware->handle($request);
                    if ($adminCheck) return $adminCheck;
                    return $userController->deleteUser($id);
                }
            ]
        ]
    ],

    // Student CRUD routes (keeping your existing structure)
    'students' => [
        'middleware' => [$authMiddleware],
        'routes' => [
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
            ]
        ]
    ]
];