<?php
// File: /mvc/controllers/ViewController.php
namespace mvc\controllers;

class ViewController {
    public function renderView(string $viewName, array $data = []): void {
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            http_response_code(200);
            extract($data); // Makes array keys into variables
            include $viewPath;
        } else {
            http_response_code(404);
            echo "View '{$viewName}' not found.";
        }
    }
}
