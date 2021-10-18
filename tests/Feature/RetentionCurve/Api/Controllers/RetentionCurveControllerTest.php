<?php

namespace Tests\Feature\RetentionCurve\Api\Controllers;

use Tests\TestCase;

class RetentionCurveControllerTest extends TestCase
{
    public function test_get_retention_curves()
    {
        dump('test_get_retention_curves');
        $response = $this->getJson("/api/retention-curves/index");
        $response->assertOk();


        $response->assertJsonStructure([
            [
                'week',
                'data' => []]
        ]);
    }
}
