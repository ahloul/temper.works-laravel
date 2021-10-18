<?php

namespace App\RetentionCurve\Api\Controllers;

use App\RetentionCurve\Usecases\Interfaces\HandleDataInterface;


class RetentionCurveController
{
    private $handleData;
    public function __construct(HandleDataInterface $handleData)
    {
        $this->handleData=$handleData;
    }

    public function index()
    {

       $data= $this->handleData->handel();
        return response()->json($data);

    }
}
