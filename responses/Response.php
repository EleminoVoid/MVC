<?php
// File: /mvc/responses/Response.php
namespace mvc\responses;

class Response {
    private $statusCode;
    private $body;
    private $headers;

    public function __construct($statusCode, $body, $headers = []) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeaders() {
        return $this->headers;
    }
}