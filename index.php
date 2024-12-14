<?php

use App\Middlewares\Http\CSRFMiddleware;
use App\Router\Router;


require_once "./vendor/autoload.php";



$router = new Router();

$router->get('' , "Homecontroller@index")->middleware([CSRFMiddleware::class]);


$router->dispatch();

