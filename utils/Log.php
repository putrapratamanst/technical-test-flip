<?php

namespace utils;

class Log
{

    private static $filePath = __DIR__ . "/../logs/";
    private static $fileName;
    private static $fullPath;

    public function __construct()
    {
    }

    public static function info(string $log, $logName = "")
    {
        $infoLog = "Info : $log";

        self::write($infoLog, $logName);
    }

    public static function error(string $log, $logName = "")
    {
        $errorLog = "Error : $log";

        self::write($errorLog, $logName);
    }

    private static function write(string $log, $logName = null)
    {
        $dateTime = Date("Y-m-d H:i:s");

        $today = explode(' ', $dateTime)[0];

        self::$fileName = empty($logName) ? "debug-$today.log" : $logName;
        self::$fullPath = self::$filePath . self::$fileName;

        $formattedLog = "[$dateTime] $log\n";

        if (file_exists(self::$fullPath)) {
            $file = fopen(self::$fullPath, "a");
        } else {
            $file = fopen(self::$fullPath, "w");
        }

        fwrite($file, $formattedLog);
        fclose($file);
    }
}
