<?php
namespace mvc\requests;

use mvc\classes\RequestInterface;

class Request implements RequestInterface {
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath(): string {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($path, '/');
    }

    public function getBody(): array {
        $data = [];
        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
        }
        return $data;
    }
}