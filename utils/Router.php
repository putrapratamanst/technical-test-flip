<?php

namespace utils;

use helpers\constants\ErrorConstant;
use helpers\constants\HttpConstant;
use helpers\constants\MessageConstant;
use helpers\http\Request;
use utils\exceptions\BaseException;

class Router
{
    private static $availableRoutes = [];

    private static function registerRoute($path, $method, $function)
    {
        if (empty($path))
            $path = "/";

        self::$availableRoutes[$path][$method] = $function;
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

    public static function run()
    {
        try {
            $getUrlParsed = parse_url($_SERVER['REQUEST_URI']);
            $getPath      = $getUrlParsed['path'];
            $method       = $_SERVER['REQUEST_METHOD'];

            if (!isset(self::$availableRoutes[$getPath])) {
                throw new BaseException(
                    MessageConstant::MESSAGE_CODE_404_NOT_FOUND,
                    ErrorConstant::ERROR_CODE_404_NOT_FOUND,
                    404
                );
            }

            if (!isset(self::$availableRoutes[$getPath][$method])) {
                throw new BaseException(
                    MessageConstant::MESSAGE_CODE_405_NOT_ALLOWED,
                    ErrorConstant::ERROR_CODE_405_NOT_ALLOWED,
                    405
                );
            }

            $getCurrentFunction = self::$availableRoutes[$getPath][$method];
            self::executeFunction($getCurrentFunction);
            return;
        } catch (BaseException $e) {
            Log::error($e);
            $e->send();
        }
    }

    private static function executeFunction($function)
    {
        $extractFunction = self::extractFunction($function);
        $controller      = new $extractFunction['controller']();
        $function        = $extractFunction['function'];
        $request         = new Request();

        $controller->$function($request);
        return;
    }

    private static function extractFunction($function)
    {
        $function       = explode("@", $function);
        $controllerName = "controllers\\" . $function[0];
        $functionName   = $function[1];
        
        return ['controller' => $controllerName, 'function' => $functionName];
    }
}
