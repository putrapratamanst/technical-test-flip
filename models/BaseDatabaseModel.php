<?php

namespace models;

use utils\database\adaptors\mysql\Mysql;

class BaseDatabaseModel extends Mysql {

    protected $database;
    protected $connection;

}
