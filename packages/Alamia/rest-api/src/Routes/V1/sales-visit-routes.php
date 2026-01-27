<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\SalesVisit\SalesVisitController;

Route::group([
    'prefix'     => 'sales-visits',
    'middleware' => ['auth:sanctum', 'jsonapi'],
], function () {
    Route::get('/', [SalesVisitController::class, 'index']);
    Route::get('/{id}', [SalesVisitController::class, 'show']);
    Route::post('/', [SalesVisitController::class, 'store']);
    Route::put('/{id}', [SalesVisitController::class, 'update']);
    Route::delete('/{id}', [SalesVisitController::class, 'destroy']);
});
