<?php
namespace mvc\requests;

use mvc\classes\RequestInterface;
use mvc\middlewares\RouteMatcher;
use mvc\responses\Response;

class Router {
    private $request;
    private $routeMatcher;
    private $routes = [];

    public function __construct(RequestInterface $request, RouteMatcher $routeMatcher) {
        $this->request = $request;
        $this->routeMatcher = $routeMatcher;
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $match = $this->routeMatcher->match(
            $this->routes,
            $this->request->getMethod(),
            $this->request->getPath()
        );

        if ($match) {
            return call_user_func_array($match['handler'], array_values($match['params']));
        }

        // 404 logic
        ob_start();
        include __DIR__ . '/../views/404.php';
        $content = ob_get_clean();
        return new Response(404, $content, ['Content-Type' => 'text/html']);
    }
}