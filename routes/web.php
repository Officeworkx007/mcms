<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\MediatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (hidden behind a configurable secret slug)
|--------------------------------------------------------------------------
*/
Route::prefix(config('admin.panel_slug'))
    ->name('admin.')
    ->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
            Route::post('login', [AdminAuthController::class, 'login']);

            Route::get('register', [AdminAuthController::class, 'showRegister'])->name('register');
            Route::post('register', [AdminAuthController::class, 'register']);
        });

        Route::middleware(['auth', 'admin'])->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

            Route::get('dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            Route::resource('mediators', MediatorController::class)->names('mediators');
        });
    });

require __DIR__ . '/auth.php';
