<?php

namespace App\RetentionCurve\Services;

use App\RetentionCurve\Interfaces\ReadFileInterface;
use Illuminate\Support\Facades\Storage;

class GetDataFromTsvFile implements ReadFileInterface
{


    public function getDataFromFile(string $filename) :array
    {
        $filePath = Storage::disk('files')->path($filename);
        $file = fopen($filePath, "r");

        $parsedData=[];
        //to skip first line
        fgetcsv($file);
        while ( ($row = fgetcsv($file, 0, "\t")) !==FALSE ) {
            $data=[];
            $data['user_id'] = $row[0];
            $data['created_at'] = $row[1];
            $data['onboarding_perentage'] = $row[2];

            array_push($parsedData, $data);

        }
        fclose($file);
        return $parsedData;
    }
}
