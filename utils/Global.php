<?php

if (!function_exists('config')) {
    function config($configName = "", $key = "")
    {

        $default = "";

        if (empty($configName))
            return $default;
        

        $configPath = __DIR__ . "/../config/$configName.php"; // declare file path 

        if (!file_exists($configPath)) 
            return $default;
        

        $config = require __DIR__ . "/../config/$configName.php"; //get content file

        if (empty($key))
            return $config;        

        if (!isset($config[$key])) 
            return $default;

        return $config[$key];
    }


if(!function_exists('env')) {

        function env($key, $default = null)
        {
            return $_ENV[$key] ?? $default;;
        }
    }
}
