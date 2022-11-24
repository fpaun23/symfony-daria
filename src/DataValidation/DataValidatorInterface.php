<?php

namespace App\DataValidation;

interface DataValidatorInterface
{
    public function validData(array $val):bool;
    public function errorVal():string;
}