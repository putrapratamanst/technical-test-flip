<?php

namespace services;

use helpers\Auth;
use helpers\constants\ErrorConstant;
use helpers\constants\HttpConstant;
use helpers\constants\MessageConstant;
use helpers\constants\ValidationConstant;
use helpers\Curl;
use models\DisburseModel;
use utils\exceptions\BaseException;

class DisburseService extends BaseService
{
    public static function create($params, $model)
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

        $secret = Auth::generateCredential();

        $curl = new Curl(env('BASE_URL_FLIP'), '/disburse', HttpConstant::HTTP_METHOD_POST);
        $curl->intialize($params);
        $curl->setHeader("Authorization", "basic $secret");
        $curl->setHeader('Content-type', 'application/x-www-form-urlencoded');
        $curl->callEndpoint();
        $data = $curl->respBody();

        if (empty($data->id)) {
            throw new BaseException(
                MessageConstant::MESSAGE_CREATE_FAILED,
                ErrorConstant::ERROR_CODE_CREATE_FAILED,
                400
            );
        }

        $requestBody = serialize($params);
        $responseBody = serialize($data);

        $createDisburse = $model->createDisburse($data, $requestBody, $responseBody);

        if (empty($createDisburse)) {
            throw new BaseException(
                MessageConstant::MESSAGE_CREATE_FAILED,
                ErrorConstant::ERROR_CODE_CREATE_FAILED,
                400
            );
        }

        return $createDisburse;

    }
}
