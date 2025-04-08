<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Controllers\LeadController;

Route::get('/submissions', [FormSubmissionController::class, 'index'])->name('submissions.index');
Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function() {
    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
    Route::delete('leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::get('leads/{id}', [LeadController::class, 'show'])->name('leads.show');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
