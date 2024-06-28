<?php

use App\Http\Controllers\ColumnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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


Route::middleware('auth')->group(function () {
    Route::post('/profile/uploadImage', [ProfileController::class, 'uploadImage'])->name('profile.uploadImage');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('project', ProjectController::class)->only('show');

    Route::post('column/swap', [ColumnController::class, 'swap'])->name('column.swap');
    Route::resource('column', ColumnController::class)->only('store', 'destroy');

    Route::post('task/{task}/move', [TaskController::class, 'move'])->name('task.move');

    Route::resource('task', TaskController::class)->only('store', 'destroy');

    Route::post('/image', [ImageController::class, 'store'])->name('image.store');
    Route::delete('/image/{image}', [ImageController::class, 'destroy'])->name('image.destroy');

});

require __DIR__.'/auth.php';
