<?php
// File: /mvc/controllers/ViewController.php
namespace mvc\controllers;

class ViewController {
    public function renderView(string $viewName, array $data = []): array {
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            ob_start();
            extract($data); 
            include $viewPath;
            $content = ob_get_clean(); 
            return [
                'status' => 200,
                'html' => $content
            ];
        } else {
            return [
                'status' => 404,
                'error' => "View '{$viewName}' not found."
            ];
        }
    }
}
