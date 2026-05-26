<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\Controllers\Api\TicketController;
use App\Http\Middleware\StoreTokenMiddleware;


Route::middleware(['throttle:ticket_ratelimit'])->post('/ticketApi', [TicketController::class, 'store'])->middleware(StoreTokenMiddleware::class);
Route::middleware(['throttle:ticket_ratelimit'])->post('/{id}/replyApi', [TicketController::class, 'reply'])->middleware(StoreTokenMiddleware::class);
