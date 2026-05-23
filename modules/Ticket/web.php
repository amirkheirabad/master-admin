<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\Controllers\Web\TicketController;

Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('list_tickets');
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('show_ticket');
    Route::get('/insert_ticket', [TicketController::class, 'insert'])->name('insert_ticket');
    Route::middleware(['throttle:ticket_ratelimit'])->post('/{id}/ticket_reply', [TicketController::class, 'replyAsAdmin'])->name('ticket_reply');
    Route::middleware(['throttle:ticket_ratelimit'])->post('/ticket_store', [TicketController::class, 'store']);
});

Route::get('/refresh-captcha', [TicketController::class, 'refreshCaptcha'])->name('refresh.captcha');



Route::middleware(['check.login', 'check.role:admin,seller'])->group(function(){
    Route::get('/tickets', [TicketController::class, 'index'])->name('list_tickets');
    Route::get('/insert_ticket', [TicketController::class, 'insert'])->name('insert_ticket');
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('show_ticket');
    Route::middleware(['throttle:ticket_ratelimit'])->post('/tickets_store_admin', [TicketController::class, 'storeUser'])->name('tickets_store_admin');
    Route::middleware(['throttle:ticket_ratelimit'])->post('/{id}/reply_store', [TicketController::class, 'replyUser'])->name('ticket_store_reply');
    Route::post('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets_update_status');

});
