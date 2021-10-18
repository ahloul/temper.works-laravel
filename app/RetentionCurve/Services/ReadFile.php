<?php

namespace App\RetentionCurve\Services;

use App\RetentionCurve\Interfaces\ReadFileInterface;

class ReadFile
{
    private $stratgy;

    public function __construct(ReadFileInterface $stratgy)
    {
        $this->stratgy = $stratgy;
    }

    public function setStratgey(ReadFileInterface $stratgy)
    {
        $this->stratgy = $stratgy;
    }

    public function getDataFromFile(string $fileName)
    {
        return $this->stratgy->getDataFromFile($fileName);
    }
}


