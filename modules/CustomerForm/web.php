<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerForm\Controllers\Web\PublicFormController;

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/f/{token}', [PublicFormController::class, 'show'])->name('customer-forms.public.show');
    Route::post('/f/{token}', [PublicFormController::class, 'submit'])->name('customer-forms.public.submit');
});
