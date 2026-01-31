<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\AiDiscoveryController;

Route::group(['prefix' => 'ai'], function () {
    Route::post('discovery', [AiDiscoveryController::class, 'store']);
});
