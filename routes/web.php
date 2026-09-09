<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\MediatorController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\CaseCategoryController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage.index');
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

            Route::resource('cases', CaseController::class)->names('cases');

            Route::resource('categories', CaseCategoryController::class)->names('categories');

            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

            Route::get('/reports/mediation-summary', [ReportController::class, 'mediationSummary'])->name('reports.mediation-summary');

            Route::get('/reports/mediator-summary', [ReportController::class, 'mediatorSummary'])->name('reports.mediator-summary');

            Route::get('/reports/case-summary', [ReportController::class, 'caseSummary'])->name('reports.case-summary');

            Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
        });
    });

require __DIR__ . '/auth.php';
