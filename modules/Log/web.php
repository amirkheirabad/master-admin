<?php

use Illuminate\Support\Facades\Route;
use Modules\Log\Controllers\LogController;

Route::middleware(['check.login', 'check.role:admin'])->group(function() {
    Route::get('/log-ticket', [LogController::class, 'indexTicket'])->name('log-ticket');
    Route::get('/log-factor', [LogController::class, 'indexFactor'])->name('log-factor');
    Route::get('/log-smsPanel', [LogController::class, 'indexSmsPanel'])->name('log-smsPanel');
    Route::get('/reports/tickets', [LogController::class, 'ticketReports'])->name('reports-tickets');
    Route::get('/reports/store-checklists', [LogController::class, 'storeChecklistLogs'])->name('reports-store-checklists');
});
