<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormSubmissionController;

Route::get('/submissions', [FormSubmissionController::class, 'index'])->name('submissions.index');
Route::get('/', function () {
    return view('welcome');
});


