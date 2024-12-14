<?php

namespace App\Router;

use App\Middlewares\Middleware;
use Core\Request\Request;

class Dispatcher {
    private Request $request;

    public function __construct(private array $routes){
        $this->request = new Request;
    }

    public function dispatch()
    {
        $matchedRoute = null;
    
        // Find the first matching route
        foreach ($this->routes as $route) {
            $match = $this->matchRoute($route);
            if ($match) {
                $matchedRoute = $match;
                break; // Exit the loop once a match is found
            }
        }
    
        if ($matchedRoute) {

      

            $controllerName = $matchedRoute['class']['controller'];
            $actionName = $matchedRoute['class']['action'];
            $params = $matchedRoute['params'] ?? []; // Only the route parameters
    
            // Ensure all parameters have default null values if not set
            $reflectedMethod = new \ReflectionMethod($controllerName, $actionName);
            $parameters = $reflectedMethod->getParameters();
            $args = [];
    
            foreach ($parameters as $parameter) {
                $name = $parameter->getName();
                $args[] = $params[$name] ?? null;
            }
    


            // Instantiate the controller
            if (class_exists($controllerName)) {

                if(isset($matchedRoute['middleware'])){
                    $middleware = new Middleware($matchedRoute['middleware']);
                    //$middleware->Check();
                }

                $controller = new $controllerName();
    
                // Check if the method exists on the controller
                if (method_exists($controller, $actionName)) {
                    return call_user_func_array([$controller, $actionName], $args);
                } else {
                    // Handle the error (method not found)
                    throw new \Exception("Method $actionName not found in controller $controllerName");
                }
            } else {
                // Handle the error (controller class not found)
                throw new \Exception("Controller $controllerName not found");
            }
        }
    
        // Handle the case where no route matches
        echo ("No route matched the request");
    }

    private function matchRoute(array &$route) {
        // Check if the HTTP method matches
        if ($route['request'] === $this->request->getMethod()) {
            // Perform the regex match
            if (preg_match($route['route'], $this->request->getPath(), $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key) && !is_numeric($key)) {
                        $params[$key] = $value;
                    }
                }
                $route['params'] = $params;
                $route['queries'] = $this->request->getQueries();
                return $route;
            }
        }
    }
}
