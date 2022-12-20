<?php

namespace App\Service;

use App\Service\FileReaderServiceInterface;

class JobService
{
    public FileReaderServiceInterface $reader;
    public function __construct(FileReaderServiceInterface $reader)
    {
        $this->reader = $reader;
    }
}
