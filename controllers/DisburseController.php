<?php

namespace controllers;

use helpers\http\Request;
use models\DisburseModel;
use services\DisburseService;
use utils\response\BaseResponse;

class DisburseController
{

    public function index()
    {
        $response = new BaseResponse();
        $form     = __DIR__ . "/../views/disburse/form.php";

        $response->withView($form);
        return $response->view();
    }

    public function create(Request $request)
    {
        $response = new BaseResponse();
        $model    = new DisburseModel();
        $params   = $model->reformatBody($request->getBody());
        $disburse_created = DisburseService::create($params);

        $response->withData($disburse_created);
        $response->withDataTransformer(new DisburseTransformer());
        return $response->send();
    }
}
