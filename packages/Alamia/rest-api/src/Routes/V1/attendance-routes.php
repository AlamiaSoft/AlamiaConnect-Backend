<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\Attendance\AttendanceController;

Route::group([
    'prefix'     => 'attendance',
    'middleware' => ['auth:sanctum', 'jsonapi'],
], function () {
    Route::get('/', [AttendanceController::class, 'index']);
    Route::post('/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/check-out', [AttendanceController::class, 'checkOut']);
});
