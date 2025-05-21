<?php
// namespace mvc\middlewares;

// use mvc\requests\Request;
// use mvc\responses\Response;

// class RouteDispatcher
// {
//     private $routeGroups;
//     private $request;
    
//     public function __construct(array $routeGroups, Request $request)
//     {
//         $this->routeGroups = $routeGroups;
//         $this->request = $request;
//     }
    
//     public function dispatch()
//     {
//         $path = $this->request->getPath();
//         $method = $this->request->getMethod();

//         foreach ($this->routeGroups as $groupName => $groupConfig) {
//             $prefix = $groupConfig['prefix'] ?? '';

//             foreach ($groupConfig['routes'] as $route) {
//                 if (is_array($route) && isset($route['routes'])) {
//                     $nestedGroup = [
//                         'prefix' => $prefix . ($route['prefix'] ?? ''),
//                         'middleware' => array_merge(
//                             $groupConfig['middleware'] ?? [],
//                             $route['middleware'] ?? []
//                         ),
//                         'routes' => $route['routes']
//                     ];

//                     $nestedDispatcher = new self(['nested' => $nestedGroup], $this->request);
//                     $response = $nestedDispatcher->dispatch();

//                     if ($response !== null) {
//                         return $response;
//                     }
//                     continue;
//                 }

//                 $fullPath = $prefix . $route['path'];
//                 $params = [];
//                 if (
//                     $route['method'] === $method &&
//                     $this->matchesRoute($fullPath, $path, $params)
//                 ) {
//                     // Run middleware stack
//                     foreach ($groupConfig['middleware'] ?? [] as $middleware) {
//                         $response = $middleware->handle($this->request);
//                         if ($response !== null) {
//                             return $response; // Middleware blocked the request
//                         }
//                     }

//                     // Call handler with params if present
//                     return call_user_func_array($route['handler'], $params);
//                 }
//             }
//         }

//         return new Response(404, [
//             'error' => 'Not Found',
//             'message' => 'The requested resource was not found'
//         ]);
//     }

//     /**
//      * Matches a route pattern (with {param}) to a path.
//      * Fills $params with extracted values.
//      */
//     private function matchesRoute(string $routePattern, string $path, array &$params = []): bool
//     {
//         $paramNames = [];
//         $regex = preg_replace_callback('/\{([^}]+)\}/', function ($matches) use (&$paramNames) {
//             $paramNames[] = $matches[1];
//             return '([^/]+)';
//         }, $routePattern);

//         $regex = '#^' . $regex . '$#';

//         if (preg_match($regex, $path, $matches)) {
//             array_shift($matches); // Remove full match
//             $params = $matches;
//             return true;
//         }
//         return false;
//     }
// }