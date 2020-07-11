<?php

namespace utils\response;

use helpers\Constants\ErrorConstant;
use helpers\Constants\MessageConstant;

class BaseResponse
{
    private $success;
    private $errorCode;
    private $message;
    private $data;
    private $view;
    private $transformer;

    public function __construct()
    {
        $this->success   = true;
        $this->errorCode = ErrorConstant::ERROR_CODE_DEFAULT;
        $this->message   = MessageConstant::MESSAGE_DEFAULT;
    }

    public function withError($error = false, $errorCode = null, $code = 400)
    {
        $this->success = !$error;
        $this->errorCode = $errorCode;
        http_response_code($code);
    }

    public function withMessage($message = "")
    {
        $this->message = $message;
    }

    public function withData($data = [])
    {
        $this->data = $data;
    }

    public function withView($pathView)
    {
        $this->view = $pathView;
    }

    public function withDataTransformer($transformer)
    {
        $this->transformer = $transformer;
    }

    public function send()
    {
        $jsonBody = $this->transform();

        header('Content-type: application/json');
        echo json_encode($jsonBody);
        return;
    }

    private function transform()
    {
        $response = [
            'success'   => $this->success,
            'errorCode' => $this->errorCode,
            'message'   => $this->message,
        ];

        if (!is_null($this->data)) {
            $response['data'] = $this->transformData();
        }

        return $response;
    }

    private function transformData()
    {
        if (empty($this->data)) 
            return $this->data;
        
        if (empty($this->transformer)) 
            return $this->data;
        
        if (gettype($this->data) == "array" && isset($this->data[0])) {
            $newData = [];

            foreach ($this->data as $data) {
                $newData[] = ($this->transformer)->transform($data);
            }
        } else {
            $newData = ($this->transformer)->transform($this->data);
        }

        return $newData;
    }

    public function view()
    {
        $view = "";

        if (file_exists($this->view)) {
            $view = include $this->view;
        } else {
            $view = $this->view;
        }

        return $view;
    }
}
