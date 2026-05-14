<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\Web\UserController;
use Modules\User\Controllers\Web\AuthController;

//user routes
Route::middleware(['check.login', 'check.role:admin'])->group(function(){
Route::get('/user-list', [UserController::class, 'index'])->name('user-list');
Route::get('/user-insert', [UserController::class, 'insert'])->name('user-insert');
Route::get('/user-edit/{id}', [UserController::class, 'edit'])->name('user-edit');
Route::put('/user-update/{id}', [UserController::class, 'update'])->name('user-update');
Route::post('/user-create', [UserController::class, 'user_create'])->name('user-create');
Route::get('/role-insert', [UserController::class, 'role_insert'])->name('role-insert');
Route::get('/role-edit/{id}', [UserController::class, 'role_edit'])->name('role-edit');
Route::put('/role-update/{id}', [UserController::class, 'role_update'])->name('role-update');
Route::get('/role-list', [UserController::class, 'role_list'])->name('role-list');
Route::post('/role-create', [UserController::class, 'role_create'])->name('role-create');
Route::delete('/role-delete/{id}', [UserController::class, 'role_delete'])->name('role-delete');
});


//auth routh
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');