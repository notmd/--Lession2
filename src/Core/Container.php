<?php

namespace App\Core;

use Exception;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected  array $instances = [];
    private static ?self $instance = null;

    public function get($dependency)
    {
        if (!$this->has($dependency)) {
            throw new Exception("$dependency is not instantiable");
        }

        return $this->instances[$dependency];
    }

    public function has($dependency): bool
    {
        return isset($this->instances[$dependency]);
    }

    public function bind($abstract, $concrete = null)
    {
        if (is_callable($concrete)) {
            $concrete = $concrete($this);
        }
        if ($concrete === null) {
            $concrete = new $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
