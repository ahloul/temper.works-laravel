<?php

namespace App\Providers;

use App\RetentionCurve\Repositories\Classes\RetentionCurveRepositoryFiles;
use App\RetentionCurve\Repositories\Interfaces\RetentionCurveRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProviders extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            RetentionCurveRepositoryInterface::class,
            RetentionCurveRepositoryFiles::class
        );
    }
}
