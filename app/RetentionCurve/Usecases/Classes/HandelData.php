<?php

namespace App\RetentionCurve\Usecases\Classes;

use App\RetentionCurve\Repositories\Interfaces\RetentionCurveRepositoryInterface;
use App\RetentionCurve\Usecases\Interfaces\HandleDataInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HandelData implements HandleDataInterface
{
    private $retentionCurveRepository;

    public function __construct(RetentionCurveRepositoryInterface $retentionCurveRepository)
    {
        $this->retentionCurveRepository = $retentionCurveRepository;
    }

    public function handel()
    {

        $data = $this->retentionCurveRepository->getAllData();
        $collection = collect($data);

        $collection = $this->groupDataByWeek($collection);

        $data = $this->groupDataByOnboardingPerentage($collection);
        $data = $this->addMissingPoints($data);
        $data= $this->transformData($data);
        return $data;

    }

    private function groupDataByWeek(Collection $collection)
    {
//        dd($collection);
        return $collection->groupBy(function ($user) {
            $created_at = Carbon::parse($user['created_at']);
            $start = $created_at->startOfWeek()->format('Y-m-d');

            return $start;

        });
    }

    private function groupDataByOnboardingPerentage(Collection $collection)

    {

        $userPercentage = [];
        foreach ($collection as $startOfWeek => $userData) {
            $userPercentage[$startOfWeek] = $userData->groupBy(function ($date) {
                return $date['onboarding_perentage'];
            })->map->count();
        }
        return $userPercentage;
    }

    private function addMissingPoints($arr)
    {

//1.	Create account - 0%
//2.	Activate account - 20%
//3.	Provide profile information - 40%
//4.	What jobs are you interested in? - 50%
//5.	Do you have relevant experience in these jobs? - 70%
//6.	Are you a freelancer? - 90%
//7.	Waiting for approval - 99%
//8.	Approval - 100%

        $array[100] = [99, 90, 70, 50, 40, 20, 0];
        $array[99] = [90, 70, 50, 40, 20, 0];
        $array[90] = [70, 50, 40, 20, 0];
        $array[70] = [50, 40, 20, 0];
        $array[50] = [40, 20, 0];
        $array[40] = [20, 0];
        $array[20] = [0];
        $array[0] = [0];
        array_walk_recursive($arr, function (collection &$OnboardingUsersCount, $startOfWeek) use ($array) {

            //sort data desc for push missing point.
            // remove not valid points
            $OnboardingUsersCount = $OnboardingUsersCount->sortKeys(0, true)->filter(function ($value, $key) use ($array) {
                if ($key != '' && in_array($key, array_keys($array))) {
                    return true;
                }
                return false;
            });

           //add missing points
            $OnboardingUsersCount->map(function ($count, $point) use ($OnboardingUsersCount, $array) {
                $missingPoints = $array[$point];
                foreach ($missingPoints as $value) {
                    if (!$OnboardingUsersCount->has($value)) {
                        $OnboardingUsersCount->put($value, 0);
                    }
                }
            });

            //sort again to make sure array is descending
            $OnboardingUsersCount = $OnboardingUsersCount->sortKeys(0, true);
            $usersCount = $OnboardingUsersCount->values();
            $onboardingPoinrs = $OnboardingUsersCount->keys();
            $sum = $usersCount->sum();

            // sum recursively user count
            for ($i = 1; $i < count($usersCount); $i++) {
                $usersCount[$i] += $usersCount[$i - 1];
            }
            //convert user count to percentage
            foreach ($usersCount as $key => $userCountAtThisPoint) {
                $userCountAtThisPointPrecentage = ($userCountAtThisPoint / $sum) * (100);

                $OnboardingUsersCount->put($onboardingPoinrs[$key], $userCountAtThisPointPrecentage);
            }
        });
        return $arr;

    }

    private function transformData($arr)
    {
        $parsedData = [];

        foreach ($arr as $startOfWeek => $pointsAndUsersCountPercentage) {
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
