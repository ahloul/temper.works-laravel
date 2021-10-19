<?php

namespace App\RetentionCurve\Api\Controllers;

use App\RetentionCurve\Transformer\Interfaces\HandelDataTransformerInterface;
use App\RetentionCurve\Usecases\Interfaces\HandleDataInterface;


class RetentionCurveController
{
    private $handleData;
    private  $handleDataTransformer;
    public function __construct(HandleDataInterface $handleData,HandelDataTransformerInterface $handleDataTransformer)
    {
        $this->handleData=$handleData;
        $this->handleDataTransformer=$handleDataTransformer;
    }

    public function index()
    {

       $data= $this->handleData->handel();
       $transformedData=$this->handleDataTransformer->transform($data);
        return response()->json($transformedData);

    }
}
