<?php
namespace App\RetentionCurve\Interfaces;

interface ReadFileInterface
{
    public function getDataFromFile(string $filename) :array;
}
