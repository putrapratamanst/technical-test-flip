<?php

namespace services;

use helpers\constants\ValidationConstant;

class BaseService
{
    protected static function validate(array $params, array $validator)
    {
        $requiredFields = [];
        foreach ($validator as $key => $val) {
            if (count($val) > 0) {
                foreach ($val as $valueValidator) {
                    if ($valueValidator == ValidationConstant::VALIDATION_STRING && empty($params[$key])) {
                        $requiredFields[] = $key . " Is Required";
                    } if ($valueValidator == ValidationConstant::VALIDATION_NUMBER) {
                        $numberValidation = self::numberValidation($params[$key]);
                        if ($numberValidation == false) {
                            $requiredFields[] = $key . " Must Be Number";
                        }
                    }
                    else if ($valueValidator == ValidationConstant::VALIDATION_STRING) {
                        $stringValidation = self::stringValidation($params[$key]);
                        if ($stringValidation == false) {
                            $requiredFields[] = $key . " Must Be String";
                        }
                    }
                }
            }
        }

        return $requiredFields;
    }

    private static function numberValidation($value)
    {
        if (is_int($value))
            return true;

        return false;
    }

    private static function stringValidation($value)
    {
        if (is_string($value))
            return true;

        return false;
    }
}
