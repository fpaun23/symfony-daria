<?php

namespace App\Service;

use App\ConstantContact;
use App\Service\DataValidatorInterface;

class DataValidatorService implements DataValidatorInterface
{
    private $err_msg = "";

    /**
     * validating submitted data
     *
     * @param  mixed $val
     * @return bool
     */
    public function validData(array $val): bool
    {
        if (( strlen($val[0]) < ConstantContact::NAME[0] || strlen($val[0]) > ConstantContact::NAME[1]) || !preg_match("/^[a-zA-Z]+$/", $val[0])) {
            $this -> err_msg = $this -> err_msg  . ConstantContact :: NAME_ERROR;
        }

        if (!filter_var($val[2], FILTER_VALIDATE_EMAIL)) {
            $this -> err_msg = $this-> err_msg . ConstantContact:: EMAIL_ERROR;
        }

        if (strlen($val[1]) < ConstantContact::DESC[0] || strlen($val[1]) > ConstantContact::DESC[1]) {
            $this -> err_msg = $this -> err_msg . ConstantContact :: DESC_ERROR;
        }
        if (empty($this -> errorMesage)) {
            return true;
        }
            return false;
    }
    public function errorVal(): string
    {
        return $this->err_msg;
    }
}
