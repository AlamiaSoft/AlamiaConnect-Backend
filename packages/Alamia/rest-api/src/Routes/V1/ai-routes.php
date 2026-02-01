<?php

use Alamia\RestApi\Http\Controllers\V1\AiDiscoveryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ai'], function () {
    Route::post('discovery', [AiDiscoveryController::class, 'store']);
    Route::post('chat', [AiProxyController::class, 'chat']);
});
