<?php


namespace App\Router;


abstract class RouterFactory{


    public static function extractControllerAndAction(string $class){
        $class = explode('@' , $class);
        return ["controller" => $class[0] , "action" => $class[1]];
    }

    
    public static function convertRouteIntoRegex(string $route){
        $route = trim($route,"/");
        $route = str_replace("/" , "\/" , $route);
        $route = preg_replace_callback_array(
            [
                "/\{([\w\d]+)\}/" => fn($matches) => "(?P<$matches[1]>[\w]+)",
                "/\{([\w\d]+)\?\}/" => fn($matches) => "?(?P<$matches[1]>[\w]+)?",
            ],
            $route
        );

//Str0ng@Password


        $route = '/^'.$route.'$/';
        return $route;
    }

    public static function buildTheRouter(array $routes){
        self::hasNamespace($routes);
        self::hasMiddlewares($routes);

        return $routes;
    }


    private static function hasNamespace(array &$routes)
    {
        $baseNamespace = 'App\\Controllers\\';
    
        foreach ($routes as $key => &$route) {
            if (isset($route['class']['controller'])) {
                if (isset($route['namespace'])) {
                    // If a sub-namespace is specified, add it between base namespace and controller
                    $route['class']['controller'] = $baseNamespace . $route['namespace'] . '\\' . $route['class']['controller'];
                    // Remove the namespace key as it's no longer needed
                    unset($route['namespace']);
                } else {
                    // If no sub-namespace, just prepend the base namespace
                    $route['class']['controller'] = $baseNamespace . $route['class']['controller'];
                }
            }
        }
        return $routes;
    }



    private static function hasMiddlewares(array &$routes)
    {
        foreach ($routes as &$route) {
            if (isset($route['middleware'])) {
                // Check if middleware is a string or an array, but don't concatenate any namespace
                if (is_string($route['middleware'])) {
                    // No namespace concatenation needed
                    $route['middleware'] = $route['middleware'];
                } elseif (is_array($route['middleware'])) {
                    // No namespace concatenation for each middleware in the array
                    foreach ($route['middleware'] as &$middleware) {
                        $middleware = $middleware;
                    }
                }
            }
        }
    
        return $routes;
    }
    


}