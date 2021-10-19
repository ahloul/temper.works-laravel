<?php

namespace Tests\Unit;

use App\RetentionCurve\Transformer\Classes\HandelDataTransformer;
use App\RetentionCurve\Usecases\Classes\HandelData;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class HandelDataTransformerTest extends TestCase
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

    public function test_transformData()
    {
        $handelDataTransformer=new HandelDataTransformer();
        dump("testing function transformData");
        $data = [
            "2016-07-18" => collect([
                100 => 60,
                99 => 100,
                90 =>  100,
                70 =>  100,
                50 =>  100,
                40 =>  100,
                20 => 100,
                0 =>  100,
            ])
        ];

        $validReturn = [
            [
                "week" => "2016-07-18",
                "data" => [
                    [
                        "x" => 100,
                        "y" => 60,
                    ],
                    [
                        "x" => 99,
                        "y" => 100,
                    ],
                    [
                        "x" => 90,
                        "y" => 100,
                    ],
                    [
                        "x" => 70,
                        "y" => 100,
                    ],
                    [
                        "x" => 50,
                        "y" =>100,
                    ],
                    [
                        "x" => 40,
                        "y" => 100,
                    ],
                    [
                        "x" => 20,
                        "y" => 100,
                    ],
                    [
                        "x" => 0,
                        "y" => 100,
                    ]
                ]
            ]
        ];

        $return = $handelDataTransformer->transform($data);
        $this->assertEquals($validReturn, $return);


    }


}
