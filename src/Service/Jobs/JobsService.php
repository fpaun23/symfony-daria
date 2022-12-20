<?php

namespace App\Service;

use App\Service\FileReaderServiceInterface;

class JobsService
{
    public FileReaderServiceInterface $reader;
    public function __construct(FileReaderServiceInterface $reader)
    {
        $this->reader = $reader;
    }
}
