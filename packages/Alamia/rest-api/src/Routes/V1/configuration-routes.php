<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\Configuration\ConfigurationController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('configuration/{slug?}', [ConfigurationController::class, 'store']);
});
