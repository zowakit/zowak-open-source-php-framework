<?php
namespace App\Middlewares\Http;
use App\Middlewares\MiddlewareInterface;

class CSRFMiddleware extends MiddlewareInterface
{
    public function Check($request = null)
    {
         $this->Next($request . __CLASS__);
    }
}
