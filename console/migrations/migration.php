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

/* 
    CREATE SCHEMA DATABASE
 */
$sql = "CREATE DATABASE " . $databaseConfig['db_database'];
if ($connection->query($sql) === TRUE) {
    echo "Database " . $databaseConfig['db_database'] . " Created Successfully\n";
} else {
    echo "Error creating database: " . $connection->error . "\n";
}

$connection->close();

/* 
    CREATE TABLE DISBURSED
 */
$connection = openDatabaseConnection($serverName, $databaseConfig, $databaseConfig['db_database']);

$table = "disburse";

$sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        disbursed_id BIGINT(20) DEFAULT NULL,
        amount DECIMAL(20,2) DEFAULT NULL,
        status VARCHAR(20) DEFAULT NULL,
        bank_code VARCHAR(6) DEFAULT NULL,
        account_number VARCHAR(20) DEFAULT NULL,
        beneficiary_name VARCHAR(255) DEFAULT NULL,
        remark TEXT DEFAULT NULL,   
        receipt TEXT DEFAULT NULL,
        time_served DATETIME DEFAULT NULL,
        fee DECIMAL(20,2) DEFAULT NULL,
        third_party VARCHAR(255) DEFAULT NULL,
        request_raw TEXT DEFAULT NULL,
        response_raw TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT NULL,
        updated_at DATETIME DEFAULT NULL
    )";

if ($connection->query($sql) === TRUE) {
    echo "Table $table Created Successfully\n";
} else {
    echo "Error Creating Table: " . $connection->error . "\n";
}

$connection->close();
