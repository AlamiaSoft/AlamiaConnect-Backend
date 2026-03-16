<?php

use Alamia\Admin\Http\Controllers\Billing\WebhookController;
use Illuminate\Support\Facades\Route;

/**
 * Billing routes.
 */
Route::post('billing/webhook', [WebhookController::class, 'handle'])
    ->name('alamia.api.v1.billing.webhook');
