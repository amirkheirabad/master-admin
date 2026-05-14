<?php

use Illuminate\Support\Facades\Route;
use Modules\Factor\Controllers\Web\FactorController;


Route::middleware(['check.login', 'check.role:admin,seller'])->group(function(){
    Route::get('/factor-list', [FactorController::class, 'index'])->name('factor-list');
Route::get('/factor-insert', [FactorController::class, 'insert'])->name('factor-insert');
Route::get('/category-list', [FactorController::class, 'index_category'])->name('category-list');
Route::post('/insert-category', [FactorController::class, 'insert_category'])->name('insert-category');
Route::get('/category/{id}', [FactorController::class, 'show']);
Route::put('/update-category/{id}', [FactorController::class, 'update_category'])->name('update-category');
Route::delete('/delete-category/{id}', [FactorController::class, 'category_delete'])->name('delete-category');
Route::get('/factor', [FactorController::class, 'factor_index'])->name('factor');


Route::post('/factor-create', [FactorController::class, 'storeFactor'])->name('factor-create');
Route::delete('/delete-factor/{id}', [FactorController::class, 'deleteFactor'])->name('delete-factor');
Route::get('/factor-edit/{id}', [FactorController::class, 'factor_edit'])->name('factor-edit');
Route::post('/factor-update/{id}', [FactorController::class, 'updateFactor'])->name('factor-update');
Route::get('/factor/{id}', [FactorController::class, 'showFactor'])->name('factor-show');
Route::get('/factor/hash/{id}', [FactorController::class, 'getHash']);


Route::get('/pay/{hash}', [FactorController::class, 'pay']);
    });
