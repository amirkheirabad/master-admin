<?php

use Illuminate\Support\Facades\Route;
use Modules\SmsPanel\Controllers\Api\SmsPanelController;
use App\Http\Middleware\StoreTokenMiddleware;

Route::post('/send-sms-panel', [SmsPanelController::class, 'createFromToken'])->name('store-requests')->middleware(StoreTokenMiddleware::class);
Route::put('/update-sms-panel', [SmsPanelController::class, 'updateFromToken'])->middleware(StoreTokenMiddleware::class);


