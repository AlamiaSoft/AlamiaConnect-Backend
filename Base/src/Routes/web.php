<?php

use Illuminate\Support\Facades\Route;
use Sam\Base\Http\Controllers\BaseController;

Route::prefix('base')->group(function () {
    Route::get('', [BaseController::class, 'index'])->name('admin.base.index');
});
