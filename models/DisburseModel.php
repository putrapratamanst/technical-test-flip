<?php

namespace models;

class DisburseModel extends BaseDatabaseModel {
{

    public function reformatBody($body)
    {
        $newBody = [
            'bank_code'      => $body['bank_code'],
            'account_number' => (int)$body['account_number'],
            'amount'         => (int)$body['amount'],
            'remark'         => $body['remark'],
        ];

        return $newBody;
    }
}
