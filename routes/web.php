<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EarningController;
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

// 👇 rutas para datos del dashboard
Route::get('/dashboard-data', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.data');

Route::get('/dashboard-models', [DashboardController::class, 'models'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.models');

Route::middleware('auth')->group(function () {
    // Perfil (solo ver/editar el propio perfil)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:view own profile')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:view own profile')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware('permission:view own profile')
        ->name('profile.destroy');

    // Ganancias (Modelo, Admin y S-Admin pueden registrar)
    Route::post('/earnings/store', [EarningController::class, 'store'])
        ->middleware('permission:register earnings')
        ->name('earnings.store');

    // Solo S-Admin puede editar/eliminar ganancias
    Route::patch('/earnings/{earning}', [EarningController::class, 'update'])
        ->middleware('permission:edit earnings')
        ->name('earnings.update');

    Route::delete('/earnings/{earning}', [EarningController::class, 'destroy'])
        ->middleware('permission:delete earnings')
        ->name('earnings.destroy');
});

require __DIR__.'/auth.php';
