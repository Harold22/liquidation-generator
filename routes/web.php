<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SDOController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group.
|
*/

// Guest routes
Route::get('/', fn () => view('auth.login'))->name('login.view');

// Authenticated and Verified Users
Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/cash-advances/add', 'cash-advances.cash-advance')->name('cash-advance.add');
    Route::view('/cash-advances/list', 'cash-advances.cash-advance-list')->name('cash-advance.list');
    Route::view('/import-files', 'import-files.import-files')->name('import-files');
    Route::view('/rcd/{id}', 'rcd')->name('rcd');
    Route::view('/cdr/{id}', 'cdr')->name('cdr');
    Route::view('/liquidation-report/{id}', 'liquidation-report')->name('liquidation-report');
});

// Admin-only routes
Route::middleware(['auth', 'role:Admin', 'active'])->group(function () {
    Route::view('/users', 'users')->name('users');
    Route::get('/getUsers', [UserController::class, 'index']);
    Route::post('user', [UserController::class, 'store'])->name('register.store');
    Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update.status');
    Route::get('/get-activity-logs', [ActivityLogController::class, 'index']);
    Route::view('/activity-logs', 'activity-logs')->name('logs');
    Route::post('/user/reset/{id}', [UserController::class, 'resetPassword']);

    // sdo routes
    Route::view('/sdo', 'sdo')->name('sdo');
    Route::post('/sdo', [SDOController::class, 'store'])->name('sdo.store');
    Route::get('/getSDO/index', [SDOController::class, 'index']);
    Route::post('/sdo/update', [SDOController::class, 'update'])->name('sdo.update');
    Route::delete('sdo/delete/{id}', [SDOController::class, 'destroy']);

});

// Active user routes
Route::middleware(['auth', 'active'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/getSDOList', [SDOController::class, 'getSDOList']);

    // Feature route files
    require __DIR__.'/cash_advance.php';
    require __DIR__.'/files.php';
    require __DIR__.'/refund.php';
    require __DIR__.'/dashboard.php';
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
