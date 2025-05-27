<?php
namespace mvc\controllers;

use mvc\classes\DataRepositoryInterface;
use mvc\classes\RequestInterface;
use mvc\responses\Response;

class StudentController {
    private $studentRepository;
    private $request;

    public function __construct(DataRepositoryInterface $studentRepository, RequestInterface $request) {
        $this->studentRepository = $studentRepository;
        $this->request = $request;
    }

    // GET /api/students
    public function getAllStudents() {
        $students = $this->studentRepository->getAll();
        return new Response(200, json_encode($students), ['Content-Type' => 'application/json']);
    }

    // GET /api/students/{id}
    public function getStudentById($id) {
        $student = $this->studentRepository->getById($id);
        if (!$student) {
            return new Response(404, json_encode(['error' => 'Student not found']), ['Content-Type' => 'application/json']);
        }
        return new Response(200, json_encode($student), ['Content-Type' => 'application/json']);
    }

    // Helper to detect API request
    private function isApiRequest() {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return (strpos($contentType, 'application/json') !== false) || (strpos($accept, 'application/json') !== false);
    }

    // Helper to set flash error and redirect
    private function flashErrorAndRedirect($message, $redirectUrl) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash_error'] = $message;
        header('Location: ' . $redirectUrl);
        exit;
    }

    // Helper to get redirect URL with page param
    private function getRedirectUrlWithPage($default = '/students') {
        $page = null;
        if (isset($_GET['page'])) {
            $page = (int)$_GET['page'];
        } elseif (isset($_POST['page'])) {
            $page = (int)$_POST['page'];
        }
        return $page ? $default . '?page=' . $page : $default;
    }

    // POST /api/students or /students (web)
    public function createStudent() {
        $data = $this->request->getBody();
        $isApi = $this->isApiRequest();
        $redirectUrl = $this->getRedirectUrlWithPage();
        if (empty($data['name']) || empty($data['email'])) {
            if ($isApi) {
                return new Response(400, json_encode(['error' => 'Name and email are required']), ['Content-Type' => 'application/json']);
            } else {
                $this->flashErrorAndRedirect('Name and email are required.', '/students/create');
            }
        }
        $this->studentRepository->create($data);
        if ($isApi) {
            return new Response(201, json_encode(['message' => 'Student created']), ['Content-Type' => 'application/json']);
        } else {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    // PUT /api/students/{id} or /students/{id}/edit (web)
    public function updateStudent($id) {
        $data = $this->request->getBody();
        $isApi = $this->isApiRequest();
        $redirectUrl = $this->getRedirectUrlWithPage();
        if (empty($data['name']) || empty($data['email'])) {
            if ($isApi) {
                return new Response(400, json_encode(['error' => 'Name and email are required']), ['Content-Type' => 'application/json']);
            } else {
                $this->flashErrorAndRedirect('Name and email are required.', '/students/' . $id . '/edit');
            }
        }
        // Check if student exists first
        $student = $this->studentRepository->getById($id);
        if (!$student) {
            if ($isApi) {
                return new Response(404, json_encode(['error' => 'Student not found']), ['Content-Type' => 'application/json']);
            } else {
                $this->flashErrorAndRedirect('Student not found.', $redirectUrl);
            }
        }
        $result = $this->studentRepository->update($id, $data);
        if ($isApi) {
            return new Response(200, json_encode(['message' => 'Student updated']), ['Content-Type' => 'application/json']);
        } else {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    // DELETE /api/students/{id}
    public function deleteStudent($id) {
        $isApi = $this->isApiRequest();
        $redirectUrl = $this->getRedirectUrlWithPage();
        $result = $this->studentRepository->delete($id);
        if ($result === 0) {
            if ($isApi) {
                return new Response(404, json_encode(['error' => 'Student not found']), ['Content-Type' => 'application/json']);
            } else {
                $this->flashErrorAndRedirect('Student not found.', $redirectUrl);
            }
        }
        if ($isApi) {
            return new Response(200, json_encode(['message' => 'Student deleted']), ['Content-Type' => 'application/json']);
        } else {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}
