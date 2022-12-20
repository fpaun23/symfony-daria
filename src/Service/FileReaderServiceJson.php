<?php

namespace App\Service;

use App\Controller\JobController;
use App\Validator\job\JobDataValidator;
use App\Service\FileReaderServiceInterface;

class FileReaderServiceJson implements FileReaderServiceInterface
{
    public function getData(): array
    {
        if (!file_exists("App\Service\File\jobs.json")) {
            throw new \Exception('please load the file because it is not loaded.');
        } else {
            $data = [];
            $json_data = file_get_contents("App\Service\File\jobs.json");
            $data[] = json_decode($json_data, JSON_OBJECT_AS_ARRAY);
             //var_dump($data);
             //die;
            return $data;
        }
    }
}
