<?php
namespace mvc\controllers;

use mvc\responses\Response;

class ViewController
    {
    private $studentRepository;
    private $request;
        public function __construct($studentRepository, $request) {
            $this->studentRepository = $studentRepository;
            $this->request = $request;
        }

        public function showLoginForm() {
        ob_start();
        include __DIR__ . '/../views/login.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showRegisterForm() {
        ob_start();
        include __DIR__ . '/../views/register.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }
    
    public function showStudentList() {
        $students = $this->studentRepository->getAll();
        ob_start();
        include __DIR__ . '/../views/studentlist.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showCreateForm() {
        ob_start();
        include __DIR__ . '/../views/student_create.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showEditForm($id) {
        $student = $this->studentRepository->getById($id)[0] ?? null;
        if (!$student) {
            return $this->notFoundResponse();
        }
        ob_start();
        include __DIR__ . '/../views/student_edit.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }


    
    private function notFoundResponse() {
        return new Response(404, json_encode(['error' => 'Student not found']));
    }

    private function createdResponse() {
        return new Response(201, json_encode(['message' => 'Student created']));
    }

    private function successResponse($message) {
        return new Response(200, json_encode(['message' => $message]));
    }

}