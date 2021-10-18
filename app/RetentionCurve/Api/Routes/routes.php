<?php

declare(strict_types = 1);

Route::group(['prefix'=>'retention-curves','as'=>'retentionCurve.'], function () {
    Route::get('/index',     [\App\RetentionCurve\Api\Controllers\RetentionCurveController::class, 'index']
    )->name('get.index');
});
