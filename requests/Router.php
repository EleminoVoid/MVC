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

    public function addRoute($method, $path, $handler, $middleware = []) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch() {
        $match = $this->routeMatcher->match(
            $this->routes,
            $this->request->getMethod(),
            $this->request->getPath()
        );

        if ($match) {
            // Fix: Ensure $match['middleware'] is always an array
            $middlewares = isset($match['middleware']) && is_array($match['middleware']) ? $match['middleware'] : [];
            foreach ($middlewares as $middleware) {
                $result = $middleware->handle($this->request);
                if ($result !== null) {
                    return $result;
                }
            }
            return $match['handler'](...array_values($match['params']));
        }

        ob_start();
        include __DIR__ . '/../views/404.php';
        $content = ob_get_clean();
        return new Response(404, $content, ['Content-Type' => 'text/html']);
    }
}
$method = $route['method'] ?? '';
if ($method !== '') {
    $method = strtoupper($method);
}