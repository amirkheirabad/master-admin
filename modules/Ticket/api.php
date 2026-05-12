<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\Controllers\Api\TicketController;
use App\Http\Middleware\StoreTokenMiddleware;


Route::post('/', [TicketController::class, 'store'])->middleware(StoreTokenMiddleware::class);
Route::post('/{id}/reply', [TicketController::class, 'reply'])->name('ticket_store_reply')->middleware(StoreTokenMiddleware::class);
