<?php

namespace utils\databases\adaptors\mysql;

use helpers\constants\ErrorConstant;
use helpers\constants\MessageConstant;
use utils\exceptions\BaseException;
use mysqli;
use utils\Log;

class Mysql extends Connection {

    protected $data;

    protected function connect() {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

        if ($conn->connect_error) {
            Log::error("Database : Couldn't connect to database");
            throw new BaseException(MessageConstant::MESSAGE_INTERNAL_SERVER_ERROR, ErrorConstant::ERROR_CODE_UNKNOWN, 500);
        }

        return $conn;
    }

    public function get() {
        $data = [];

        $sql = $this->buildQuery();

        $conn = $this->connect();

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $newObj = new $this;

                foreach ($row as $key => $val) {
                    if (property_exists($newObj, $key)) {
                        $newObj->$key = $val;
                    }
                }

                $data[] = $newObj;
            }
        }

        $conn->close();

        return $data;
    }

    public function first() {
        $data = NULL;

        $sql = $this->buildQuery();

        $conn = $this->connect();

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $data = new $this;
            foreach ($row as $key => $val) {
                if (property_exists($data, $key)) {
                    $data->$key = $val;
                }
            }
        }

        $conn->close();

        return $data;
    }

    public function create() {
        $data = NULL;

        $sql = $this->buildQuery();

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            $id = $conn->insert_id;
            $data = $this->findById($id);
        } else {
            Log::error("Database : Failed to create data ". $conn->error);
        }

        $conn->close();

        return $data;
    }

    public function update() {
        $data = NULL;

        $sql = $this->buildQuery();

        $conn = $this->connect();

        if ($conn->query($sql) === TRUE) {
            $data = $this->findById($this->id);

        } else {
            Log::error("Database : Failed to update data " . $conn->error);
        }

        $conn->close();

        return $data;
    }

    private function findById($id) {
        return $this->setQuery(
            "SELECT * FROM " . $this->table . " WHERE id=? LIMIT 1" 
        )->setBindings([
            $id
        ])->first();
    }
}
