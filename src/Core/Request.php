<?php

namespace App\Core;

class Request
{
    public static function uri(): string
    {
        return trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
    }

    public static function fullUri(): string
    {
        return trim($_SERVER["REQUEST_URI"]);
    }

    public static function query(): array
    {
        $res = null;
        parse_str((string) parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), $res);

        return $res;
    }

    public static function method(): string
    {
        if (isset($_REQUEST['_method'])) {
            $method = strtoupper($_REQUEST['_method']);

            if ($method == 'PUT' || $method == 'DELETE') {
                return $method;
            }
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    public static function get(string $key, $default = null)
    {
        return static::has($key) ? $_REQUEST[$key] : $default;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_REQUEST);
    }
}
