<?php


namespace App\Middlewares;



class Middleware{

    public function __construct(private array $Middlewares){}
    
    public function Check($request = null)
    {
        $MiddlewareObjs = [];
    
        // Instantiate all middleware classes and store them in an array
        if (isset($this->Middlewares)) {
            foreach ($this->Middlewares as $Middleware) {
                $MiddlewareObjs[] = new $Middleware;
            }
    
            // Link each middleware to the next one
            $count = count($MiddlewareObjs);
            for ($i = 0; $i < $count - 1; $i++) {
                $MiddlewareObjs[$i]->NextToCheck = $MiddlewareObjs[$i + 1];
            }
    
            // Call the Check method on the first middleware in the chain
            if (isset($MiddlewareObjs[0])) {
              return  $MiddlewareObjs[0]->Check($request);
            }
        }
    }
    

}