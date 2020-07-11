<?php

namespace utils\databases\adaptors\mysql;

use utils\Log;

class Connection {

    protected $host;
    protected $port;
    protected $username;
    protected $password;
    protected $database_name;
    protected $servername;
    protected $query;
    protected $bindings = [];

    public function __construct($config = []) {
        if (empty($config)) {
            $config = config('database', 'disburse_db');
        }

        $this->host = $config['db_host'] ?? "";
        $this->port = $config['db_port'] ?? "";
        $this->username = $config['db_username'] ?? "";
        $this->password = $config['db_password'] ?? "";
        $this->database_name =  $config['database_name'] ?? "";
        $this->servername = $this->host . ":" . $this->port;
    }

    protected function connect() {}

    public function setQuery($sql) {
        $this->query = $sql;
        return $this;
    }

    public function setBindings(array $bindings) {
        $this->bindings = $bindings;
        return $this;
    }

    protected function buildQuery() {
        $sql = $this->query;

        foreach ($this->bindings as $bind) {
            if (gettype($bind) === "array") {
                $value = "(" . implode("', '", $bind) . ")";
            } else {
                $value = $bind;
            }

            if (!empty($value)){
                if ($value == "0000-00-00 00:00:00") {
                    $value = "NULL";
                } else {
                    $value = "'$value'";
                }
            }

            if (is_null($value)) {
                $value = "NULL";
            }

            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        Log::info($sql);

        return $sql;
    }
}
