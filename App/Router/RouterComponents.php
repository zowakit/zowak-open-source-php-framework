<?php

namespace App\Router;

class RouterComponents {
    protected $data;

    // Static method to create an instance with data
    public static function createWithData(&$data) {
        $instance = new self();
        $instance->data = &$data;
        return $instance;
    }

    public function namespace(string $namespace) {
        $this->data['namespace'] = $namespace;
        return $this;
    }

    public function middleware(array $middleware) {
        $this->data['middleware'] = $middleware;
        return $this;
    }

}
