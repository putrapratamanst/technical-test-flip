<?php

use utils\Router;

//this is routing
$router = new Router();

$router->get('/', "DisburseController@index");
$router->post('/disburse', "DisburseController@index");
$router->patch('/disburse', "DisburseController@index");
$router->get('/list', "DisburseController@index");

$router->run();
