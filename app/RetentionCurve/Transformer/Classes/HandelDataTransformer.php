<?php

namespace App\RetentionCurve\Transformer\Classes;

use App\RetentionCurve\Transformer\Interfaces\HandelDataTransformerInterface;

class HandelDataTransformer implements HandelDataTransformerInterface
{
    public function transform(array $dataArray):array
    {
        $parsedData = [];

        foreach ($dataArray as $startOfWeek => $pointsAndUsersCountPercentage) {
            $singleWeekData['week'] = $startOfWeek;
            $data=[];

            foreach ($pointsAndUsersCountPercentage as $onboardingPoint => $userCountPercentage) {

                $data[] = ['x' => $onboardingPoint, 'y' => $userCountPercentage];
            }

            $singleWeekData['data'] = $data;
            $parsedData[] = $singleWeekData;
        }
        return $parsedData;
    }

}
