<?php

use Illuminate\Support\Facades\Route;
use Modules\SmsPanel\Controllers\Web\SmsPanelController;

Route::get('/sms-panel', [SmsPanelController::class, 'index'])->name('sms-panel');
Route::post('/sms-store/{id}', [SmsPanelController::class, 'store'])->name('sms-store');
Route::get('/getSms/{id}', [SmsPanelController::class, 'getSms']);
Route::get('/index_stores', [SmsPanelController::class, 'index_stores'])->name('index_stores');
