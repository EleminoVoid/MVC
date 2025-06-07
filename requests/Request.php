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
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $rawData = file_get_contents('php://input');
            
            if (strpos($contentType, 'application/json') !== false) {
                return json_decode($rawData, true);
            }
            
            parse_str($rawData, $data);
            return $data;
        }
        
        return $_POST;
    }
}