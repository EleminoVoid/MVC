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

    public function getAllStudents() {
        $students = $this->studentRepository->getAll();
        return new Response(200, json_encode($students), ['Content-Type' => 'application/json']);
    }

    public function getStudentById($id) {
        $student = $this->studentRepository->getById($id);
        
        // Debug logging
        error_log('Student data for ID ' . $id . ': ' . print_r($student, true));
        
        if (empty($student)) {
            error_log('Empty student result');
            return $this->notFoundResponse();
        }
        
        // For direct array responses
        if (is_array($student) && !isset($student[0]) && isset($student['id'])) {
            return new Response(200, json_encode($student), ['Content-Type' => 'application/json']);
        }
        
        // For results wrapped in a numeric array
        if (isset($student[0])) {
            return new Response(200, json_encode($student[0]), ['Content-Type' => 'application/json']);
        }
        
        error_log('Student found but in unexpected format');
        return $this->notFoundResponse();
    }

    public function createStudent() {
        $data = $this->request->getBody();
        
        // Validate input data
        if (empty($data['name']) || empty($data['email'])) {
            return new Response(400, json_encode(['error' => 'Name and email are required']), ['Content-Type' => 'application/json']);
        }

        $this->studentRepository->create($data);
        $content = <<<HTML
        <h2>Student Created</h2>
        <p>The student was created successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students">
    HTML;
        return new Response(201, $content, ['Content-Type' => 'text/html']);
    }

    public function updateStudent($id) {
        $data = $this->request->getBody();
        
        // Validate input data
        if (empty($data['name']) || empty($data['email'])) {
            return new Response(400, json_encode(['error' => 'Name and email are required']), ['Content-Type' => 'application/json']);
        }

        $this->studentRepository->update($id, $data);
        $content = <<<HTML
        <h2>Student Updated</h2>
        <p>The student was updated successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students">
    HTML;
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function deleteStudent($id) {
        $this->studentRepository->delete($id);
        $content = <<<HTML
        <h2>Student Deleted</h2>
        <p>The student was deleted successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students">
    HTML;
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    private function notFoundResponse() {
        return new Response(404, json_encode(['error' => 'Student not found']), ['Content-Type' => 'application/json']);
    }
}
