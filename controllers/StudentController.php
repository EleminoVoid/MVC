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
        return new Response(200, json_encode($student));
    }

    
     public function createStudent() {
        $data = $this->request->getBody();
        $this->studentRepository->create($data);
        header('Location: /students');
        exit;
    }

    public function updateStudent($id) {
        $data = $this->request->getBody();
        unset($data['_method']); 
        $this->studentRepository->update($id, $data);
        header('Location: /students');
        exit;
    }

    public function deleteStudent($id) {
        // Now uses DBORM via repository
        $this->studentRepository->delete($id);
        header('Location: /students');
        exit;
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