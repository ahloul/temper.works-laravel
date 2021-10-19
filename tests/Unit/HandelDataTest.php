<?php

namespace Tests\Unit;

use App\RetentionCurve\Usecases\Classes\HandelData;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class HandelDataTest extends TestCase
{
    private $mockedInstance;
    protected function setUp(): void
    {
        //mock class
        $mockedInstance = $this->getMockBuilder(HandelData::class)
            ->disableOriginalConstructor()    // you may need the constructor on integration tests only
            ->getMock();
        $this->mockedInstance = $mockedInstance;
    }
    public function test_groupDataByWeek()
    {
        dump("testing function groupDataByWeek");

        $data = [
            [
                "user_id" => 3124,
                "created_at" => "2016-07-19",
                "onboarding_perentage" => 100,
                "count_applications" => 0,
                "count_accepted_applications" => 0,
            ],
            [
                "user_id" => 3126,
                "created_at" => "2016-07-20",
                "onboarding_perentage" => 100,
                "count_applications" => 0,
                "count_accepted_applications" => 0,
            ],
            [
                "user_id" => 3131,
                "created_at" => "2016-07-20",
                "onboarding_perentage" => 99,
                "count_applications" => 0,
                "count_accepted_applications" => 0,
            ],
            [
                "user_id" => 3134,
                "created_at" => "2016-07-21",
                "onboarding_perentage" => 99,
                "count_applications" => 0,
                "count_accepted_applications" => 0,
            ],
            [
                "user_id" => 3136,
                "created_at" => "2016-07-21",
                "onboarding_perentage" => 100,
                "count_applications" => 0,
                "count_accepted_applications" => 0,
            ]
        ];
        $collection = collect($data);

        //to access to private method
        $reflectedMethod = new ReflectionMethod(
            HandelData::class,
            'groupDataByWeek'
        );
        $reflectedMethod->setAccessible(true);

        $return = $reflectedMethod->invokeArgs(
            $this->mockedInstance,
            [$collection]
        );

        $vaildReturn = collect([
            "2016-07-18" => collect([
                [
                    "user_id" => 3124,
                    "created_at" => "2016-07-19",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3126,
                    "created_at" => "2016-07-20",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3131,
                    "created_at" => "2016-07-20",
                    "onboarding_perentage" => 99,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3134,
                    "created_at" => "2016-07-21",
                    "onboarding_perentage" => 99,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3136,
                    "created_at" => "2016-07-21",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ]
            ])
        ]);
        $this->assertEquals($vaildReturn, $return);


    }

    public function test_groupDataByOnboardingPerentage()
    {
        dump("testing function groupDataByOnboardingPerentage");
        $data = collect([
            "2016-07-18" => collect([
                [
                    "user_id" => 3124,
                    "created_at" => "2016-07-19",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3126,
                    "created_at" => "2016-07-20",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3131,
                    "created_at" => "2016-07-20",
                    "onboarding_perentage" => 99,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3134,
                    "created_at" => "2016-07-21",
                    "onboarding_perentage" => 99,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ],
                [
                    "user_id" => 3136,
                    "created_at" => "2016-07-21",
                    "onboarding_perentage" => 100,
                    "count_applications" => 0,
                    "count_accepted_applications" => 0,
                ]
            ])
        ]);
        $reflectedMethod = new ReflectionMethod(
            HandelData::class,
            'groupDataByOnboardingPerentage'
        );
        $reflectedMethod->setAccessible(true);

        $return = $reflectedMethod->invokeArgs(
            $this->mockedInstance,
            [$data]
        );
        $vaildReturn = [
            "2016-07-18" => collect([
                100 => 3,
                99 => 2,
            ])
        ];
        $this->assertEquals($vaildReturn, $return);


    }

    public function test_addMissingPoints()
    {
        dump("testing function addMissingPoints");
        $data = [
            "2016-07-18" => collect([
                100 => 3,
                99 => 2,
            ])
        ];
        $reflectedMethod = new ReflectionMethod(
            HandelData::class,
            'addMissingPoints'
        );
        $reflectedMethod->setAccessible(true);


        //since points is
        $points = [
            100 => 3,
            99 => 2,

        ];

        //vaild return points should be
        $validPoints = [
            100 => 3,
            99 => 5,
            90 => 5,
            70 => 5,
            50 => 5,
            40 => 5,
            20 => 5,
            0 => 5,
        ];

        $sumOfUsers = array_sum($points);
        foreach ($validPoints as $onboardingPoint => $userCount) {
            //convert user count to percentage value
            $validPoints[$onboardingPoint] = ($userCount / $sumOfUsers) * (100);
        }
        $return = $reflectedMethod->invokeArgs(
            $this->mockedInstance,
            [$data]
        );
        $vaildReturn = [
            "2016-07-18" => collect($validPoints)
        ];
        $this->assertEquals($vaildReturn, $return);


    }



}
