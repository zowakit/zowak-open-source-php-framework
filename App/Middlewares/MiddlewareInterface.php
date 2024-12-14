<?php


namespace App\Middlewares;


abstract class MiddlewareInterface {
   public $NextToCheck;


   abstract  public function Check($request=null);

   public function Next($request = null){
    if($this->NextToCheck){
     return $this->NextToCheck->Check($request);
    }
    return $request;
   }


   
}