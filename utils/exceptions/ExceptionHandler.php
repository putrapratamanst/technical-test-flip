<?php

use helpers\constants\ErrorConstant;
use utils\exceptions\BaseException;

function handleError($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return false;
    }

    $errCode = ErrorConstant::ERROR_CODE_UNKNOWN;
    $code    = 400;
    $errMsg  = $errstr;

    throw new BaseException($errMsg, $errCode, $code);
}

set_error_handler('handleError');
