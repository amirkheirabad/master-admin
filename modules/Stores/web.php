<?php

use Illuminate\Support\Facades\Route;
use Modules\Stores\Controllers\Web\StoresController;

Route::middleware('check.login')->group(function () {
    Route::get('/stores', [StoresController::class, 'list'])->name('list_stores');
    Route::get('/insert', [StoresController::class, 'index'])->name('insert_store');
    Route::get('/edit/{id}', [StoresController::class, 'edit'])->name('edit_store');
    Route::get('/store_info', [StoresController::class, 'store_info'])->name('store_info');

    Route::post('/create_store', [StoresController::class, 'store'])->name('create_store');
    Route::put('/update/{id}', [StoresController::class, 'update'])->name('update_store');
    Route::delete('delete/{id}', [StoresController::class, 'delete'])->name('delete_store');
});
