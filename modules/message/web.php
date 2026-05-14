<?php

use Illuminate\Support\Facades\Route;
use Modules\Message\Controllers\Web\MessageController;

// مسیرهای عمومی (نمایش پیام در صفحه اصلی)
Route::get('/get-messages', [MessageController::class, 'getMessagesForFront']);

// مسیرهای مدیریتی (فقط ادمین)
Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/message-list', [MessageController::class, 'index'])->name('message.list');
    Route::get('/message-insert', [MessageController::class, 'insert'])->name('message.insert');
    Route::post('/message-create', [MessageController::class, 'store'])->name('message.create');
    Route::get('/message-edit/{id}', [MessageController::class, 'edit'])->name('message.edit');
    Route::put('/message-update/{id}', [MessageController::class, 'update'])->name('message.update');
    Route::delete('/message-delete/{id}', [MessageController::class, 'destroy'])->name('message.destroy');
});