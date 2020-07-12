<?php

namespace controllers;

use helpers\http\Request;
use models\DisburseModel;
use services\DisburseService;
use utils\response\BaseResponse;

class DisburseController
{

    public function index(Request $request)
    {
        $response = new BaseResponse();
        $form     = __DIR__ . "/../views/disburse/form.php";

        $response->withView($form);
        return $response->view();
    }

    public function create(Request $request)
    {
        // $response       = new BaseResponse();
        $model          = new DisburseModel();
        $params         = $model->reformatBody($request->getBody());
        $createDisburse = DisburseService::create($params, $model);
        header('Location: /');
        // $response->withData($createDisburse);
        // return $response->send();        
    }


    public function list()
    {
        $response     = new BaseResponse();
        $disburseList = DisburseService::list();
        $response->withData($disburseList);
        return $response->send();
    }

    public function update(Request $request)
    {
        $response       = new BaseResponse();
        $model          = new DisburseModel();
        $params         = $request->getBody();
        $updateDisbursed = DisburseService::update($params, $model);
        $response->withData($updateDisbursed);
        return $response->send();        
    }
}
