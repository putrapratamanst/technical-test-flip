<?php

namespace utils;

use helpers\Constants\HttpConstant;

class Router
{
    private static $availableRoutes = [];

    private static function registerRoute($path, $method, $function)
    {
        if (empty($path))
            $path = "/";

        self::$availableRoutes[$path][$method] = $function;
        die(json_encode(self::$availableRoutes));
    }

    public static function get($path, $function)
    {
        return self::registerRoute($path, HttpConstant::HTTP_METHOD_GET, $function);
    }

    public static function post($path, $function)
    {
        return self::registerRoute($path, HttpConstant::HTTP_METHOD_POST, $function);
    }

    public static function patch($path, $function)
    {
        return self::registerRoute($path, HttpConstant::HTTP_METHOD_PATCH, $function);
    }
}
