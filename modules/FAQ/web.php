<?php

use Illuminate\Support\Facades\Route;
use Modules\FAQ\Controllers\FAQController;


Route::get('/faq_insert', [FAQController::class, 'insert'])->name('faq_insert');
Route::get('/faq_edit/{id}', [FAQController::class, 'edit'])->name('faq_edit');
Route::get('/faq_list', [FAQController::class, 'index'])->name('faq_list');
Route::post('/faq_create', [FAQController::class, 'create'])->name('faq_create');
Route::post('/faq_update/{id}', [FAQController::class, 'update'])->name('faq_update');
Route::delete('/faq_delete/{id}', [FAQController::class, 'delete'])->name('faq_delete');
Route::get('/faq_show', [FAQController::class, 'show'])->name('faq_show');

