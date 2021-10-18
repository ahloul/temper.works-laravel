<?php

namespace App\RetentionCurve\Services;

use App\RetentionCurve\Interfaces\ReadFileInterface;
use Illuminate\Support\Facades\Storage;

class GetDataFromJsonFile implements ReadFileInterface
{
    public function getDataFromFile(string $filename) :array
    {
        $json = Storage::disk('local')->get($filename);
        return json_decode($json,true);
    }
}
