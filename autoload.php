<?php

function __autoload($class_name)
{
    $class_name = explode("\\", $class_name);
    $class_name = implode("/", $class_name);
    $file = __DIR__ . '/' . $class_name . '.php';

    if (file_exists($file)) {
        require_once($file);
        return;
    }
}
