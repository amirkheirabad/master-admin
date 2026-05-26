<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Controllers\Web\DashboardController;

Route::middleware('check.login')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
