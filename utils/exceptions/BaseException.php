<?php

namespace utils\exceptions;

use Exception;
use Throwable;
use utils\response\BaseResponse;

class BaseException extends Exception
{
    protected $errorCode;
    protected $code;
    protected $errorMessage;

    public function __construct(
        $errorMessage,
        $errorCode,
        $code = 400,
        Throwable $previous = null
    ) {
        parent::__construct($errorMessage, $code, $previous);   
        $this->errorCode    = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->code         = $code;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function send()
    {
        $response = new BaseResponse();

        $response->withError(true, $this->errorCode, $this->code);
        $response->withMessage($this->errorMessage);
        return $response->send();
    }

}
