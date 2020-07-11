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

        $input = $_POST;

        if ($input) {
            $this->body = $input;
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
