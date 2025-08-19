<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TextAnalyzerController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('analyzer.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/analyzer', [TextAnalyzerController::class, 'index'])->name('analyzer.index');
    Route::get('/analyzer/create', [TextAnalyzerController::class, 'create'])->name('analyzer.create');
    Route::post('/analyzer', [TextAnalyzerController::class, 'store'])->name('analyzer.store');
    Route::get('/analyzer/{id}', [TextAnalyzerController::class, 'show'])->name('analyzer.show');
    Route::delete('/analyzer/{id}', [TextAnalyzerController::class, 'destroy'])->name('analyzer.destroy');


});

require __DIR__.'/auth.php';
