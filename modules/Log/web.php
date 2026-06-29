<?php

use Illuminate\Support\Facades\Route;
use Modules\Log\Controllers\LogController;

Route::middleware(['check.login', 'check.role:admin'])->group(function() {
    Route::get('/log-ticket', [LogController::class, 'indexTicket'])->name('log-ticket');
    Route::get('/log-factor', [LogController::class, 'indexFactor'])->name('log-factor');
});
