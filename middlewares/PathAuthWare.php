<?php
// File: /mvc/middlewares/PathAuthWare.php
namespace mvc\middlewares;

use mvc\request\Request;
use mvc\response\Response;

class PathAuthWare
{
    private $routeGroups;
    private $request;
    
    public function __construct(array $routeGroups, Request $request)
    {
        $this->routeGroups = $routeGroups;
        $this->request = $request;
    }
    
    public function dispatch()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        
        foreach ($this->routeGroups as $groupName => $groupConfig) {
            $prefix = $groupConfig['prefix'] ?? '';
            
            foreach ($groupConfig['routes'] as $routeKey => $route) {
                // Handle nested groups
                if (is_array($route) && isset($route['routes'])) {
                    $nestedGroup = [
                        'prefix' => $prefix . ($route['prefix'] ?? ''),
                        'middleware' => array_merge(
                            $groupConfig['middleware'] ?? [],
                            $route['middleware'] ?? []
                        ),
                        'routes' => $route['routes']
                    ];
                    
                    $nestedDispatcher = new self(['nested' => $nestedGroup], $this->request);
                    $response = $nestedDispatcher->dispatch();
                    
                    if ($response !== null) {
                        return $response;
                    }
                    continue;
                }
                
                // Check regular routes
                $fullPath = $prefix . $route['path'];
                if ($this->matchesRoute($fullPath, $path) && $route['method'] === $method) {
                    // Execute middleware stack
                    foreach ($groupConfig['middleware'] as $middleware) {
                        $response = $middleware->handle($this->request);
                        if ($response !== null) {
                            return $response; // Middleware blocked the request
                        }
                    }
                    
                    // Execute route handler
                    return $route['handler']($this->request);
                }
            }
        }
        
        return new Response(404, ['Content-Type' => 'application/json'], [
            'error' => 'Not Found',
            'message' => 'The requested resource was not found'
        ]);
    }
    
    private function matchesRoute(string $routePattern, string $path): bool
    {
        // Convert route pattern to regex
        $regex = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePattern);
        $regex = str_replace('/', '\/', $regex);
        $regex = '/^' . $regex . '$/';
        
        return (bool)preg_match($regex, $path);
    }
}