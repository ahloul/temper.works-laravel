<?php

namespace App\RetentionCurve\Api\Controllers;

class RetentionCurveTransformer
{

}
class SymptomTransformer extends Transform
{
    public function transform(Symptom $row)
    {
        // need to get user_id here
        return [
            'id' => $row->id,
            'name' => $row->name,
            'next_type' => $next,
            'allow' => $allow
        ];
    }
}
