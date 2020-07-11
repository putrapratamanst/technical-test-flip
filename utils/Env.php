<?php

namespace utils;

class Env
{

    private static $pathEnv = __DIR__ . "/../.env";

    public static function load()
    {
        if (file_exists(self::$pathEnv)) {

            $handle = fopen(self::$pathEnv, "r");

            
            while (($line = fgets($handle)) !== false) {
                $explodedLine = explode('=', $line);

                if (sizeof($explodedLine) >= 2) {
                    $key = trim($explodedLine[0]);

                    $value = trim($explodedLine[1]);

                    if (!empty($value)) $_ENV[$key] = $value;
                }
            }

            fclose($handle);
        }
    }
}
