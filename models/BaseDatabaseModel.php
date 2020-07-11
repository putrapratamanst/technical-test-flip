<?php

namespace models;

use utils\databases\adaptors\mysql\Mysql;

class BaseDatabaseModel extends Mysql
{
    protected $database;
    protected $connection;
}
