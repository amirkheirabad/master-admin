<?php

use Illuminate\Support\Facades\Route;
use Modules\Education\Controllers\Web\EducationController;


Route::get('/education-list', [EducationController::class, 'index'])->name('education-list');
Route::get('/video-category/{id}', [EducationController::class, 'show']);
Route::get('/get-all-videos', [EducationController::class, 'getVideos'])->name('get.all.videos');
Route::get('/get-videos/{categoryId}', [EducationController::class, 'getVideosByCategory'])->name('get.videos.by.category');


Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/video-edit/{id}', [EducationController::class, 'edit'])->name('video-edit');
    Route::delete('/video-delete/{id}', [EducationController::class, 'delete'])->name('video-delete');
    Route::post('/update-video/{id}', [EducationController::class, 'updateVideo'])->name('update-video');
    Route::get('/video-category-list', [EducationController::class, 'indexCategory'])->name('video-category-list');
    Route::delete('/video-category-delete/{id}', [EducationController::class, 'category_delete'])->name('video-category-delete');
    Route::post('/store-video', [EducationController::class, 'store'])->name('store-video');
    Route::post('/store-video-category', [EducationController::class, 'storeCategory'])->name('store-video-category');
    Route::get('/video-list', [EducationController::class, 'videoList'])->name('video-list');
    Route::put('/update-video-category/{id}', [EducationController::class, 'update_video_category'])->name('update-video-category');
    Route::get('/education-insert', [EducationController::class, 'insert'])->name('education-insert');
});
