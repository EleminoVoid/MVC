<?php
namespace mvc\controllers;

use mvc\responses\Response;

class ViewController {
    public function showHome() {
        ob_start();
        include __DIR__ . '/../views/homepage.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showStudentList($studentRepository, $page = 1) {
        $perPage = 6;
        $offset = ($page - 1) * $perPage;
        $students = $studentRepository->getPaginated($perPage, $offset);
        $total = $studentRepository->countAll();
        $totalPages = max(1, ceil($total / $perPage));

        $paginationLinks = '';
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                $paginationLinks .= "<strong>{$i}</strong> ";
            } else {
                $paginationLinks .= "<a href=\"/students?page={$i}\">{$i}</a> ";
            }
        }

        ob_start();
        include __DIR__ . '/../views/studentlist.php';
        $content = ob_get_clean();
        return new \mvc\responses\Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showStudentEdit($id, $studentRepository) {
        $student = $studentRepository->getById($id);
        ob_start();
        include __DIR__ . '/../views/student_edit.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showStudentDelete($id, $studentRepository) {
        $student = $studentRepository->getById($id);
        ob_start();
        include __DIR__ . '/../views/student_delete.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showLogin() {
        ob_start();
        include __DIR__ . '/../views/login.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showRegister() {
        ob_start();
        include __DIR__ . '/../views/register.php';
        $content = ob_get_clean();
        return new Response(200, $content, ['Content-Type' => 'text/html']);
    }

    public function showStudentCreate() {
        ob_start();
        include __DIR__ . '/../views/student_create.php';
        $content = ob_get_clean();
        return new \mvc\responses\Response(200, $content, ['Content-Type' => 'text/html']);
    }
}