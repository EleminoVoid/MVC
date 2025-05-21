<?php
namespace mvc;

global $viewController, $userController, $authController, $authMiddleware, $studentController;

return [
    'public' => [
        'routes' => [
   
            [
                'method' => 'GET',
                'path' => '/login',
                'handler' => function () use ($viewController) {
                    return $viewController->showLoginForm();
                }
            ],
            [
                'method' => 'POST',
                'path' => '/login',
                'handler' => function () use ($authController) {
                    return $authController->login();
                }
            ],
            [
                'method' => 'GET',
                'path' => '/register',
                'handler' => function () use ($viewController) {
                    return $viewController->showRegisterForm();
                }
            ],
            [
                'method' => 'POST',
                'path' => '/register',
                'handler' => function () use ($authController) {
                    return $authController->register();
                }
            ]
        ]
    ],

    'users' => [
        'middleware' => [$authMiddleware],
        'routes' => [
            // HTML VIEW ROUTES (for browser)
            [
                'method' => 'GET',
                'path' => '/home',
                'handler' => function () {
                    ob_start();
                    include __DIR__ . '/views/homepage.php';
                    $content = ob_get_clean();
                    return new \mvc\responses\Response(200, $content, ['Content-Type' => 'text/html']);
                }
            ],
            [
                'method' => 'GET',
                'path' => '/userlist',
                'handler' => function () {
                    ob_start();
                    include __DIR__ . '/views/userlist.php';
                    $content = ob_get_clean();
                    return new \mvc\responses\Response(200, $content, ['Content-Type' => 'text/html']);
                }
            ],
            [
                'method' => 'GET',
                'path' => '/students',
                'handler' => function () use ($viewController) {
                    return $viewController->showStudentList();
                }
            ],
            [
                'method' => 'GET',
                'path' => '/students/create',
                'handler' => function () use ($viewController) {
                    return $viewController->showCreateForm();
                }
            ],
            [
                'method' => 'GET',
                'path' => '/students/{id}/edit',
                'handler' => function ($id) use ($viewController) {
                    return $viewController->showEditForm($id);
                }
            ],
            [
                'method' => 'POST',
                'path' => '/students/{id}/edit',
                'handler' => function ($id) use ($studentController) {
                    return $studentController->updateStudent($id);
                }
            ],
            [
                'method' => 'POST',
                'path' => '/students/{id}/delete',
                'handler' => function ($id) use ($studentController) {
                    return $studentController->deleteStudent($id);
                }
            ],

            // API ROUTES (for JSON, all prefixed with /api/)
            [
                'method' => 'GET',
                'path' => '/api/users',
                'handler' => function () use ($userController) {
                    return $userController->getAllUsers();
                }
            ],
            [
                'method' => 'POST',
                'path' => '/api/users',
                'handler' => function () use ($userController) {
                    return $userController->createUser();
                }
            ],
            [
                'method' => 'GET',
                'path' => '/api/users/{id}',
                'handler' => function ($id) use ($userController) {
                    return $userController->getUserById($id);
                }
            ],
            [
                'method' => 'PUT',
                'path' => '/api/users/{id}',
                'handler' => function ($id) use ($userController) {
                    return $userController->updateUser($id);
                }
            ],
            [
                'method' => 'DELETE',
                'path' => '/api/users/{id}',
                'handler' => function ($id) use ($userController) {
                    return $userController->deleteUser($id);
                }
            ],
            [
                'method' => 'GET',
                'path' => '/api/students',
                'handler' => function () use ($studentController) {
                    return $studentController->getAllStudents();
                }
            ],
            [
                'method' => 'POST',
                'path' => '/api/students',
                'handler' => function () use ($studentController) {
                    return $studentController->createStudent();
                }
            ],
            [
                'method' => 'GET',
                'path' => '/api/students/{id}',
                'handler' => function ($id) use ($studentController) {
                    return $studentController->getStudentById($id);
                }
            ],
            [
                'method' => 'PUT',
                'path' => '/api/students/{id}',
                'handler' => function ($id) use ($studentController) {
                    return $studentController->updateStudent($id);
                }
            ],
            [
                'method' => 'DELETE',
                'path' => '/api/students/{id}',
                'handler' => function ($id) use ($studentController) {
                    return $studentController->deleteStudent($id);
                }
            ],
            [
                'method' => 'GET',
                'path' => '/logout',
                'handler' => function () {
                    setcookie('token', '', time() - 3600, '/');
                    if (session_status() === PHP_SESSION_ACTIVE) {
                        session_destroy();
                    }
                    header('Location: /login');
                    exit;
                }
            ]
        ]
    ]
];