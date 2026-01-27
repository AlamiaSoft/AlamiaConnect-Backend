<?php

use Illuminate\Support\Facades\Route;
use Alamia\Admin\Http\Controllers\Controller;

/**
 * Home routes.
 */
Route::get('/', [Controller::class, 'redirectToLogin'])->name('AlamiaConnect.home');


