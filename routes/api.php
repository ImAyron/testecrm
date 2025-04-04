<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormSubmissionController;

Route::options('/{any}', function (Request $request) {
    return response()->json([], 204)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
})->where('any', '.*');


// Remova qualquer redirecionamento acidental
Route::post('/form-submissions', function (Request $request) {
    return response()->json(['status' => 'success']);
});

// Ou se estiver usando Controller
Route::post('/form-submissions', [FormSubmissionController::class, 'store']);