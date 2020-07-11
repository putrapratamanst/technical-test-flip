<?php

use utils\Router;

//this is routing
$router = new Router();

$router->get('/', "DisburseController@index");
$router->post('/disburse', "modules\api\DisburseController@index");
$router->patch('/disburse', "modules\api\DisburseController@index");
$router->get('/list', "modules\api\DisburseController@index");

