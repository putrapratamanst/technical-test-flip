<?php

require (dirname(__DIR__) . "/../helpers/Curl.php");
require (dirname(__DIR__) . "/../utils/Log.php");
require (dirname(__DIR__) . "/../helpers/constants/DisburseConstant.php");
require (dirname(__DIR__) . "/../utils/Env.php");
require (dirname(__DIR__) . "/../utils/Global.php");
require (dirname(__DIR__) . "/../helpers/constants/HttpConstant.php");

date_default_timezone_set('Asia/Jakarta');

use helpers\constants\DisburseConstant;
use helpers\constants\HttpConstant;
use helpers\Curl;
use utils\Log;
use utils\Env;

Env::load();

$today = Date("Y-m-d");
$logName = "scheduler-$today.log";
$localIp = env('LOCAL_IP', '');
$localPort = env('LOCAL_PORT', '3005');
$localUrl = "$localIp:$localPort";

Log::info("Scheduler starting....", $logName);
$client = new Curl($localUrl, "/list", HttpConstant::HTTP_METHOD_GET);
$client->intialize(['status' => DisburseConstant::STATUS_PENDING]);

$client->callEndpoint();

$body =$client->respBody();

if (empty($body)) {
    Log::info("Failed to get pending list", $logName);
    die();
}

if (!$body->success) {
    Log::info("Failed to get pending list. reason : " . $body->message, $logName);
    die();
}

if (empty($body->data)) {
    Log::info("Nothing is processed");
    die();
}

Log::info(count($body->data) . " data will be processed", $logName);

$clientUpdate = new Curl($localUrl, "/disburse", HttpConstant::HTTP_METHOD_PATCH);

$list = $body->data;

foreach ($list as $data) {
    $id = $data->id;
    $clientUpdate->intialize(['id' => $id]);
    $clientUpdate->callEndpoint();

    $detail = $clientUpdate->respBody();
    if (empty($detail)) {
        Log::info("ID #$id Failed to update status. ID #$id", $logName);
    }
    
    if (!$detail->success) {
        Log::info("ID #$id Failed to update status. reason : " . $detail->message, $logName);
    }

    $status = $detail->data->status ?? DisburseConstant::STATUS_PENDING;

    if ($status != DisburseConstant::STATUS_PENDING) {
        Log::info("ID #$id Updated status to $status. reason : " . $detail->message, $logName);
    }
    else {
        Log::info("ID #$id status still $status.", $logName);
    }
    
}

Log::info("Scheduler end....", $logName);
