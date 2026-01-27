<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\System\StatusController;



Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('system/status', [StatusController::class, 'index']);
});
