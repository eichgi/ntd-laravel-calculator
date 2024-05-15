<?php

use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/operations', function() {
    return Inertia::render('Records');
})->middleware(['auth', 'verified'])->name('operations');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('v1')->middleware('auth')->group(function() {
    Route::post('/operation', [OperationController::class, 'calculate']);
    Route::get('/operation', [OperationController::class, 'findRecords']);
    Route::delete('/operation/{id}', [OperationController::class, 'deleteRecord']);
});

require __DIR__.'/auth.php';
