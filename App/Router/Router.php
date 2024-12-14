<?php

namespace App\Router;

use App\Router\RouterFactory;

class Router{

    protected array $routes = [];
    protected string $currentroute;
  
    public function get( string $route  , string $class ){
     return $this->addRoute($route,"GET",$class);
    }


    public function post( string $route  , string $class ){
     return $this->addRoute($route,"POST",$class);
    }

    private function addRoute(string $route  , string $request, string $class ){
    $this->currentroute = $route.":".$request.":".$class;
    $this->routes[$this->currentroute] =[
            'route' => RouterFactory::convertRouteIntoRegex($route),
            'request' => $request,
            'class' => RouterFactory::extractControllerAndAction($class)
    ];
    return RouterComponents::createWithData($this->routes[$this->currentroute]);
    }


public function dispatch(){
    $dispatcher = new Dispatcher(RouterFactory::buildTheRouter($this->routes));
    $dispatcher->dispatch();
}

}

