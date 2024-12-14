<?php
namespace App\Middlewares\Http;
use App\Middlewares\MiddlewareInterface;

class SessionMiddleware extends MiddlewareInterface
{
    public function Check($request = null)
    {
        // Modify the request and return it
        return $request . " IS BULLSHIT NOW IT'S MINE!";
    }
}
