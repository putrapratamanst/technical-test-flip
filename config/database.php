<?php

return [
    'disburse_db' => [
        'db_connection' => env('DB_CONNECTION', 'mysql'),
        'db_host' => env('DB_HOST', '127.0.0.1'),
        'db_port' => env('DB_PORT', '3306'),
        'db_database' => env('DB_DATABASE', 'disburse_db'),
        'db_username' => env('DB_USERNAME', 'root'),
        'db_password' => env('DB_PASSWORD', 'password'),
    ],
];
