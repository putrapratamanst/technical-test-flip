<?php
require_once __DIR__ . '/../../utils/Global.php';

$databaseConfig = config('database', "disburse_db");

if ($databaseConfig == "")
    die("Error : Config Database Not Exist!\n");

$serverName = $databaseConfig['db_host'] . ":" . $databaseConfig['db_port'];

function openDatabaseConnection($serverName, $databaseConfig, $dbName = NULL)
{
    return new mysqli($serverName, $databaseConfig['db_username'], $databaseConfig['db_password'], $dbName);
}

$connection = openDatabaseConnection($serverName, $databaseConfig);

if ($connection->connect_error) {
    die("Could Not Connect To Database server\n");
}

echo "Database Connected!\n";

$sql = "CREATE DATABASE " . $databaseConfig['db_database'];
if ($connection->query($sql) === TRUE) {
    echo "Database " . $databaseConfig['db_database'] . " Created Successfully\n";
} else {
    echo "Error creating database: " . $connection->error . "\n";
}

$connection->close();
