<?php

require_once __DIR__ . "/../helpers/Http/Curl/Client.php";
require_once __DIR__ . "/../helpers/Log.php";
require_once __DIR__ . "/../helpers/Constants/DisburseConstant.php";
require_once __DIR__ . "/../helpers/Env.php";
require_once __DIR__ . "/../helpers/Global.php";

date_default_timezone_set('Asia/Jakarta');

use helpers\constants\DisburseConstant;
use helpers\Curl;
use utils\Log;
use utils\Env;

Env::load();

$today = Date("Y-m-d");
$log_name = "scheduler-$today.log";
$local_ip = env('LOCAL_IP', '');
$local_port = env('LOCAL_PORT', '3005');
$local_url = "$local_ip:$local_port";

Log::info("Scheduler starting....", $log_name);

$client = new Curl($local_url, "/list", "GET");
$client->intialize(['status' => DisburseConstant::STATUS_PENDING]);
$client->callEndpoint();

$body = json_decode($client->respBody(), true);

if (empty($body)) {
    Log::info("Failed to get pending list", $log_name);
    die();
}

if (!$body['success']) {
    Log::info("Failed to get pending list. reason : " . $body['message'], $log_name);
    die();
}

if (empty($body['data'])) {
    Log::info("Nothing is processed");
    die();
}

Log::info(count($body['data']) . " data will be processed", $log_name);

$client_update = new Curl($local_url, "/disburse", "PATCH");

$list = $body['data'];

foreach ($list as $data) {
    $id = $data['id'];

    $client->intialize(['id' => $id]);
    $client->callEndpoint();
    $body = json_decode($client->respBody(), true);

    if (empty($body)) {
        Log::info("ID #$id Failed to update status. ID #$id", $log_name);
    }
    
    if (!$body['success']) {
        Log::info("ID #$id Failed to update status. reason : " . $body['message'], $log_name);
    }

    $status = $body['data']['status'] ?? DisburseConstant::STATUS_PENDING;

    if ($status != DisburseConstant::STATUS_PENDING) {
        Log::info("ID #$id Updated status to $status. reason : " . $body['message'], $log_name);
    }
    else {
        Log::info("ID #$id status still $status.", $log_name);
    }
    
}

Log::info("Scheduler end....", $log_name);
