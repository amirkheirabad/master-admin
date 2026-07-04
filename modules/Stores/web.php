<?php

use Illuminate\Support\Facades\Route;
use Modules\Stores\Controllers\Web\StoresController;

Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/stores', [StoresController::class, 'list'])->name('list_stores');
    Route::get('/insert', [StoresController::class, 'index'])->name('insert_store');
    Route::get('/edit/{id}', [StoresController::class, 'edit'])->name('edit_store');
    Route::get('/check_lists', [StoresController::class, 'checkLists'])->name('check_lists');
    Route::post('/create_check_lists', [StoresController::class, 'createCheckList'])->name('create_check_lists');
    Route::put('/update_check_lists/{id}', [StoresController::class, 'updateCheckList'])->name('update_check_lists');
    Route::get('/show_check_list/{id}', [StoresController::class, 'show'])->name('show_check_list');
    Route::delete('/delete_check_list/{id}', [StoresController::class, 'deleteCheckList'])->name('delete_check_list');
    Route::post('/update_check_list_store', [StoresController::class, 'updateCheckListsStore'])->name('update_check_list_store');
    Route::get('/store/{id}/check_lists', [StoresController::class, 'getCheckListsStores'])->name('get_check_lists_stores');

    Route::post('/create_store', [StoresController::class, 'store'])->name('create_store');
    Route::post('/update/{id}', [StoresController::class, 'update'])->name('update_store');
    Route::delete('delete/{id}', [StoresController::class, 'delete'])->name('delete_store');
    Route::post('stores/quick-create-seller', [StoresController::class, 'quickCreateSeller'])->name('quick_create_seller');
});
