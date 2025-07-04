<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CashAdvanceAllocationController;
use App\Http\Controllers\CashAdvanceController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefundController;
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
});

// Admin-only routes
Route::middleware(['auth', 'role:Admin', 'active'])->group(function () {
    Route::view('/cash-advances/add', 'cash-advances.cash-advance')->name('cash-advance.add');
    Route::view('/cash-advances/list', 'cash-advances.cash-advance-list')->name('cash-advance.list');
    Route::view('/users', 'users')->name('users');
    Route::get('/getUsers', [UserController::class, 'index']);
    Route::post('user', [UserController::class, 'store'])->name('register.store');
    Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update.status');
    Route::get('/get-activity-logs', [ActivityLogController::class, 'index']);
    Route::view('/activity-logs', 'activity-logs')->name('logs');
    Route::post('/user/reset/{id}', [UserController::class, 'resetPassword']);

    // sdo
    require __DIR__.'/sdo.php';

    //program
    require __DIR__.'/program.php';

    // cdr
    Route::view('/cdr/{id}', 'cdr')->name('cdr');

    // offices
    require __DIR__.'/offices.php';

    require __DIR__.'/cash_advance.php';
    require __DIR__.'/refund.php';

    // allocation
    Route::post('allocation', [CashAdvanceAllocationController::class, 'update'])->name('allocation.store');
    Route::get('/allocation/{id}', [CashAdvanceAllocationController::class, 'getOfficesByCashAdvance']);
    Route::get('/allocation/aggregated-data/{cash_advance_id}', [CashAdvanceAllocationController::class, 'getAggregatedData']);

});

// User-only routes
Route::middleware(['auth', 'role:User', 'active'])->group(function () {
    Route::view('/allocation-list', 'allocation-list')->name('allocation-list');
    Route::get('/user/officeName/{id}', [OfficeController::class, 'getOfficeName']);
    Route::get('/allocated/cash-advance/{id}', [CashAdvanceAllocationController::class, 'getAllLocationByOffice']);
    Route::post('/allocated/update-status', [CashAdvanceAllocationController::class, 'updateStatus'])->name('allocation.updateStatus');
    Route::view('/import-files', 'import-files.import-files')->name('import-files');
    Route::view('/rcd/{id}', 'rcd')->name('rcd');
    Route::view('/liquidation-report/{id}', 'liquidation-report')->name('liquidation-report');
    Route::get('/allocated/sdo/{office_id}', [CashAdvanceAllocationController::class, 'getAllocationBySDO']);
    Route::get('/cash-advance/details/{id}', [CashAdvanceAllocationController::class, 'getDetails'])->name('cash_advance.details');


});

// Active user routes
Route::middleware(['auth', 'active'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/getSDOList', [SDOController::class, 'getSDOList']);
    Route::get('/refund/show/{id}', [RefundController::class, 'show']);

    // Feature route files
 
    require __DIR__.'/files.php';
    require __DIR__.'/dashboard.php';
});

// Auth routes (login, register, etc.)
require __DIR__.'/auth.php';
