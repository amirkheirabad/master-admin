<?php

use Illuminate\Support\Facades\Route;
use Modules\SmsPanel\Controllers\Api\SmsPanelController;
use App\Http\Middleware\StoreTokenMiddleware;

Route::post('/store-requests', [SmsPanelController::class, 'createFromToken'])->name('store-requests')->middleware(StoreTokenMiddleware::class);
Route::post('/update-store-requests', [SmsPanelController::class, 'updateFromToken'])->middleware(StoreTokenMiddleware::class);


