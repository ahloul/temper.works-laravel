<?php

namespace App\RetentionCurve\Transformer\Interfaces;

interface HandelDataTransformerInterface
{
    public function transform(array $dataArray):array;

}
