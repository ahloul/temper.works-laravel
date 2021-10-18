<?php

namespace App\RetentionCurve\Repositories\Classes;

use App\RetentionCurve\Repositories\Interfaces\RetentionCurveRepositoryInterface;
use App\RetentionCurve\Services\GetDataFromJsonFile;
use App\RetentionCurve\Services\GetDataFromTsvFile;
use App\RetentionCurve\Services\ReadFile;

class RetentionCurveRepositoryFiles implements RetentionCurveRepositoryInterface
{
    public function getAllData() :array{
        $readFile = new ReadFile(new GetDataFromJsonFile());
        $jsonData= $readFile->getDataFromFile("export.json");

        $readFile->setStratgey(new GetDataFromTsvFile());
        $tsvData= $readFile->getDataFromFile("export.tsv");

        return array_merge($jsonData,$tsvData);
    }
}
