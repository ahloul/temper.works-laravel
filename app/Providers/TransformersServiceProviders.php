<?php

namespace App\Providers;

use App\RetentionCurve\Repositories\Classes\RetentionCurveRepositoryFiles;
use App\RetentionCurve\Repositories\Interfaces\RetentionCurveRepositoryInterface;
use App\RetentionCurve\Transformer\Classes\HandelDataTransformer;
use App\RetentionCurve\Transformer\Interfaces\HandelDataTransformerInterface;
use Illuminate\Support\ServiceProvider;

class TransformersServiceProviders extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            HandelDataTransformerInterface::class,
            HandelDataTransformer::class
        );
    }
}
