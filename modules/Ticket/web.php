<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\Controllers\Web\TicketController;

Route::middleware('check.login')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('list_tickets');
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('show_ticket');
    Route::get('/insert_ticket', [TicketController::class, 'insert'])->name('insert_ticket');
    Route::post('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets_update_status');
    Route::post('/{id}/reply', [TicketController::class, 'replyAsAdmin'])->name('ticket_reply');
    Route::post('/ticket_store', [TicketController::class, 'store']);
});
