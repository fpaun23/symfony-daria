<?php

namespace App\Service;

interface DataValidatorInterface
{
    public function validData(array $val):bool;
    public function errorVal():string;
}