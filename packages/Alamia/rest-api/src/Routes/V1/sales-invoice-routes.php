<?php

use Illuminate\Support\Facades\Route;
use Alamia\RestApi\Http\Controllers\V1\SalesInvoice\SalesInvoiceController;

Route::group([
    'prefix'     => 'sales-invoices',
    'middleware' => 'auth:sanctum',
], function () {
    Route::controller(SalesInvoiceController::class)->group(function () {
        Route::get('', 'index');
        Route::get('{id}', 'show');
        Route::post('', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
    });
});
