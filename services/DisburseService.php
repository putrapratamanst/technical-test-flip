<?php

namespace services;

use helpers\Auth;
use helpers\constants\DisburseConstant;
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

        if (empty($data->id) || $data == NULL) {
            throw new BaseException(
                MessageConstant::MESSAGE_CREATE_FAILED,
                ErrorConstant::ERROR_CODE_CREATE_FAILED,
                400
            );
        }

        $requestBody  = serialize($params);
        $responseBody = serialize($data);
        // die(json_encode($data));
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

    public static function list()
    {
        $disburse = new DisburseModel();
        $listDisburse = $disburse->findAllDisburseData();
        return $listDisburse;
    }


    public static function update($params, $model)
    {
        $validation = self::validate($params, [
            "id"      => [ValidationConstant::VALIDATION_REQUIRED],
        ]);

        if (!empty($validation)) {
            $faileds = implode(", ", $validation);
            throw new BaseException(
                $faileds,
                ErrorConstant::ERROR_CODE_CREATE_FAILED,
                400
            );
        }

        $id = $params['id'];

        $disburse = $model->findOneDisburseDataByID($id);
        if (empty($disburse)) {
            throw new BaseException(
                MessageConstant::MESSAGE_NOT_FOUND,
                ErrorConstant::ERROR_CODE_NOT_FOUND,
                400
            );
        }

        $secret = Auth::generateCredential();

        $curl = new Curl(env('BASE_URL_FLIP'), '/disburse', HttpConstant::HTTP_METHOD_GET);
        $curl->setQueryParams($disburse->disbursed_id);
        $curl->setHeader("Authorization", "basic $secret");
        $curl->setHeader('Content-type', 'application/x-www-form-urlencoded');

        $curl->callEndpoint();
        $data = $curl->respBody();

        if (empty($data->id)) {
            throw new BaseException(
                MessageConstant::MESSAGE_UPDATE_FAILED,
                ErrorConstant::ERROR_CODE_UPDATE_FAILED,
                400
            );
        }

        if ($data->status != DisburseConstant::STATUS_PENDING) {
            $disburse = $disburse->updateStatusDisburse($data);
        }

        if (empty($disburse)) {
            throw new BaseException(
                MessageConstant::MESSAGE_UPDATE_FAILED,
                ErrorConstant::ERROR_CODE_UPDATE_FAILED,
                400
            );
        }

        return $disburse;
    }
}
