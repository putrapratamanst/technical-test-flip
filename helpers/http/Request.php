<?php

namespace helpers\http;

class Request
{
    protected $body;
    protected $headers;

    public function __construct()
    {
        $this->setParams();
        $this->setHeaders();
    }

    private function setParams()
    {
        $this->body = [];

        $input = file_get_contents('php://input');
        if ($input) {
            $this->body = json_decode($input, true);
        }
        $this->body = array_merge($this->body, $_REQUEST);
    }

    private function setHeaders()
    {
        $this->headers = getallheaders();
    }

    public function getBody()
    {
         return $this->body;
    }
}
