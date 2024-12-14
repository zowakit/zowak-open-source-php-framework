<?php


namespace App\Controllers;

use PDO;

class Homecontroller {



    public function index(){
        $PDO = new PDO("mysql:host=localhost;dbname=users","root","Str0ng@Password");
        var_dump($PDO);
    }


}