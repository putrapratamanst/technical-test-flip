<?php

namespace services;

use helpers\constants\ErrorConstant;
use helpers\constants\ValidationConstant;
use utils\exceptions\BaseException;

class DisburseService extends BaseService
{
    public static function create($params)
    {
        $validation = self::validate($params, [
            "bank_code"      => [ValidationConstant::VALIDATION_REQUIRED, ValidationConstant::VALIDATION_STRING],
            "account_number" => [ValidationConstant::VALIDATION_REQUIRED, ValidationConstant::VALIDATION_NUMBER],
            "amount"         => [ValidationConstant::VALIDATION_REQUIRED, ValidationConstant::VALIDATION_NUMBER],
            "remark"         => [ValidationConstant::VALIDATION_REQUIRED, ValidationConstant::VALIDATION_STRING],
        ]);

        if (!empty($validation)) {
            $faileds = implode(", ", $validation);
            throw new BaseException(
                $faileds,
                ErrorConstant::ERROR_CODE_CREATE_FAILED,
                400
            );
        }

        die(json_encode($validation));
    }
}
