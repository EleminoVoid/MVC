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
        return new Response(200, json_encode($students));
    }

    public function getStudentById($id) {
        $student = $this->studentRepository->getById($id);
        
        if (empty($student)) {
            return $this->notFoundResponse();
        }
        
        return new Response(200, json_encode($student[0]));
    }

    public function createStudent() {
        $data = $this->request->getBody();
        $this->studentRepository->create($data);
        $content = <<<HTML
        <h2>Student Created</h2>
        <p>The student was created successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students">
    HTML;
        return new Response(201, $content, ['Content-Type' => 'text/html']);
    }

    public function updateStudent($id) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->request->getBody();
        $this->studentRepository->update($id, $data);
        $content = <<<HTML
        <h2>Student Updated</h2>
        <p>The student was updated successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students?page={$page}">
    HTML;
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function deleteStudent($id) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->studentRepository->delete($id);
        $content = <<<HTML
        <h2>Student Deleted</h2>
        <p>The student was deleted successfully.</p>
        <meta http-equiv="refresh" content="0.5;url=/students?page={$page}">
    HTML;
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