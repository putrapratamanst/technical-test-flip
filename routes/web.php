<?php

use utils\Router;

//this is routing
$router = new Router();

$router->get('/', "DisburseController@index");
$router->post('/disburse', "DisburseController@create");
$router->patch('/disburse', "DisburseController@update");
$router->get('/list', "DisburseController@list");

$router->run();
