<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TextAnalyzerApiController;
use App\Http\Controllers\Api\AuthController;


Route::prefix('v1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {



    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });


    Route::post('/send-text', [TextAnalyzerApiController::class, 'sendText']);


    Route::get('/texts', [TextAnalyzerApiController::class, 'getTexts']);
    Route::get('/texts/{id}', [TextAnalyzerApiController::class, 'getText']);
});




Route::fallback(function(){
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});
