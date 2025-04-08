<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormSubmissionController;
use App\Http\Controllers\LeadController;

Route::options('/{any}', function (Request $request) {
    return response()->json([], 204)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
})->where('any', '.*');

Route::post('leads', [LeadController::class, 'store']);


// Remova qualquer redirecionamento acidental
Route::post('/form-submissions', function (Request $request) {
    return response()->json(['status' => 'success']);
});

// Ou se estiver usando Controller
Route::post('/form-submissions', [FormSubmissionController::class, 'store']);