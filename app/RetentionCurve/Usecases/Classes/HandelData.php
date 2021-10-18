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
            $start = $created_at->startOfWeek()->format('d-m-Y');

            return $start;

        });
    }

    private function groupDataByOnboardingPerentage(Collection $collection)

    {

        $userPerceage = [];
        foreach ($collection as $startOfWeek => $userData) {
            $userPerceage[$startOfWeek] = $userData->groupBy(function ($date) {
                return $date['onboarding_perentage'];
            })->map->count();
        }
        return $userPerceage;
    }
//1.	Create account - 0%
//2.	Activate account - 20%
//3.	Provide profile information - 40%
//4.	What jobs are you interested in? - 50%
//5.	Do you have relevant experience in these jobs? - 70%
//6.	Are you a freelancer? - 90%
//7.	Waiting for approval - 99%
//8.	Approval - 100%

    private function addMissingPoints($arr)
    {

        $array[100] = [99, 90, 70, 50, 40, 20, 0];
        $array[99] = [90, 70, 50, 40, 20, 0];
        $array[90] = [70, 50, 40, 20, 0];
        $array[70] = [50, 40, 20, 0];
        $array[50] = [40, 20, 0];
        $array[40] = [20, 0];
        $array[20] = [0];
        $array[0] = [0];
        array_walk_recursive($arr, function (collection &$OnboardingUsersCount, $startOfWeek) use ($array) {
            $OnboardingUsersCount = $OnboardingUsersCount->sortKeys(0, true)->filter(function ($value, $key) use ($array) {
                if ($key != '' && in_array($key, array_keys($array))) {
                    return true;
                }
                return false;
            });


            $OnboardingUsersCount->map(function ($count, $point) use ($OnboardingUsersCount, $array) {
                $missingPoints = $array[$point];
                foreach ($missingPoints as $value) {
                    if (!$OnboardingUsersCount->has($value)) {
                        $OnboardingUsersCount->put($value, 0);
                    }
                }
            });
            $OnboardingUsersCount = $OnboardingUsersCount->sortKeys(0, true);
            $values = $OnboardingUsersCount->values();
            $keys = $OnboardingUsersCount->keys();
            $sum = $values->sum();
            for ($i = 1; $i < count($values); $i++) {
                $values[$i] += $values[$i - 1];
            }

            foreach ($values as $key => $value) {
                $new_width = ($value / $sum) * (100);

                $OnboardingUsersCount->put($keys[$key], $new_width);
            }
        });
        return $arr;

    }

    private function transformData($arr)
    {
        $arr2 = [];

        foreach ($arr as $key => $value) {
            $arr4['week'] = $key;
            $arr3=[];

            foreach ($value as $startOfWeak => $Onboarding) {

                $arr3[] = ['x' => $startOfWeak, 'y' => $Onboarding];
            }

            $arr4['data'] = $arr3;
            $arr2[] = $arr4;
        }
        return $arr2;
    }
}
