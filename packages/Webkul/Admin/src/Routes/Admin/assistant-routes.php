<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\AiAssistantController;

Route::get('ai-assistant', [AiAssistantController::class, 'index'])->name('admin.ai.assistant');
