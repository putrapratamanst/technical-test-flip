<?php 

namespace controllers;

use helpers\http\Request;
use utils\response\BaseResponse;

class DisburseController {

    public function index(){
        $response = new BaseResponse();
        $form     = __DIR__ . "/../views/disburse/form.php";

        $response->withView($form);
        return $response->view();
    }
}
