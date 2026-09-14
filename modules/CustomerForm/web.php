<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerForm\Controllers\Web\AssignmentController;
use Modules\CustomerForm\Controllers\Web\FormController;
use Modules\CustomerForm\Controllers\Web\PublicFormController;
use Modules\CustomerForm\Controllers\Web\QuestionController;
use Modules\CustomerForm\Controllers\Web\SubmissionController;

Route::middleware('throttle:30,1')->group(function () {
    Route::get('/f/{token}', [PublicFormController::class, 'show'])->name('customer-forms.public.show');
    Route::post('/f/{token}', [PublicFormController::class, 'submit'])->name('customer-forms.public.submit');
});

Route::middleware(['check.login', 'check.role:admin'])->group(function () {
    Route::get('/customer-forms', [FormController::class, 'index'])->name('customer-forms.index');
    Route::get('/customer-forms/create', [FormController::class, 'create'])->name('customer-forms.create');
    Route::post('/customer-forms', [FormController::class, 'store'])->name('customer-forms.store');
    Route::get('/customer-forms/{form}/edit', [FormController::class, 'edit'])->name('customer-forms.edit');
    Route::put('/customer-forms/{form}', [FormController::class, 'update'])->name('customer-forms.update');
    Route::get('/customer-forms/{form}/builder', [FormController::class, 'builder'])->name('customer-forms.builder');
    Route::post('/customer-forms/{form}/publish', [FormController::class, 'publish'])->name('customer-forms.publish');
    Route::get('/customer-form-questions/create', [QuestionController::class, 'create'])->name('customer-forms.questions.create');
    Route::get('/customer-form-questions/{question}/edit', [QuestionController::class, 'edit'])->name('customer-forms.questions.edit');
    Route::post('/customer-forms/{form}/questions', [QuestionController::class, 'store'])->name('customer-forms.questions.store');
    Route::put('/customer-form-questions/{question}', [QuestionController::class, 'update'])->name('customer-forms.questions.update');
    Route::delete('/customer-form-questions/{question}', [QuestionController::class, 'destroy'])->name('customer-forms.questions.destroy');
    Route::post('/customer-forms/{form}/questions/reorder', [QuestionController::class, 'reorder'])->name('customer-forms.questions.reorder');
    Route::get('/customer-form-assignments', [AssignmentController::class, 'index'])->name('customer-forms.assignments.index');
    Route::post('/customer-form-assignments', [AssignmentController::class, 'store'])->name('customer-forms.assignments.store');
    Route::patch('/customer-form-assignments/{assignment}/toggle', [AssignmentController::class, 'toggle'])->name('customer-forms.assignments.toggle');
    Route::post('/customer-form-assignments/{assignment}/rotate-token', [AssignmentController::class, 'rotate'])->name('customer-forms.assignments.rotate');
    Route::post('/customer-form-assignments/{assignment}/upgrade', [AssignmentController::class, 'upgrade'])->name('customer-forms.assignments.upgrade');
    Route::get('/customer-form-submissions', [SubmissionController::class, 'index'])->name('customer-forms.submissions.index');
    Route::get('/customer-form-submissions/{submission}', [SubmissionController::class, 'show'])->name('customer-forms.submissions.show');
});
