<?php

namespace App\Core;

use Exception;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function get(string $uri, array $controller): void
    {
        $this->addRoute($uri, 'GET', $controller);
    }

    public function post(string $uri, array $controller): void
    {
        $this->addRoute($uri, 'POST', $controller);
    }

    public function put(string $uri, array $controller): void
    {
        $this->addRoute($uri, 'PUT', $controller);
    }

    public function delete(string $uri, array $controller): void
    {
        $this->addRoute($uri, 'DELETE', $controller);
    }

    public function call(string $uri, string $requestMethod)
    {
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            $callable = $this->routes[$requestMethod][$uri];

            return call_user_func([new $callable[0], $callable[1]]);
        }

        throw new Exception('Route not found.');
    }

    private function addRoute(string $uri, string $method, array $controller)
    {
        if ($uri === '/') {
            $uri = '';
        }
        $this->routes[$method][$uri] = $controller;
    }
}
