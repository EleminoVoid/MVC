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
        if ($this->getMethod() === 'POST') {
            return $_POST ?? [];
        }
        if ($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            parse_str(file_get_contents('php://input'), $data);
            return $data ?: [];
        }
        return [];
    }
}