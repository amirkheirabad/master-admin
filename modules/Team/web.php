<?php

use Illuminate\Support\Facades\Route;
use Modules\Team\Controllers\Web\TeamController;

Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/team-list', [TeamController::class, 'index'])->name('team-list');
    Route::get('/team-insert', [TeamController::class, 'insert'])->name('team-insert');
    Route::post('/team-create', [TeamController::class, 'store'])->name('team-create');
    Route::get('/team-edit/{team}', [TeamController::class, 'edit'])->name('team-edit');
    Route::put('/team-update/{team}', [TeamController::class, 'update'])->name('team-update');
    Route::delete('/team-delete/{team}', [TeamController::class, 'destroy'])->name('team-delete');
});
