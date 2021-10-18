<?php

namespace App\Providers;

use App\RetentionCurve\Usecases\Classes\HandelData;
use App\RetentionCurve\Usecases\Interfaces\HandleDataInterface;
use Illuminate\Support\ServiceProvider;

class UseCasesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            HandleDataInterface::class,
            HandelData::class
        );
    }
}
