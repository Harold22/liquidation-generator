<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/get-beneficiaries/{year}', [DashboardController::class, 'getBeneficiariesPerMonth']);
Route::get('/dashboard/get-cash-advances/{year}', [DashboardController::class, 'getCashAdvancePerMonth']);
Route::get('/dashboard/get-sdo-status/{year}', [DashboardController::class, 'getSDOStatusPerMonth']);
Route::get('/dashboard/get-total-beneficiaries/{year}', [DashboardController::class, 'getTotalBeneficiaries']);
Route::get('/dashboard/get-total-cash-advances/{year}', [DashboardController::class, 'getTotalCashAdvances']);


// Route::post('/cash-advance/update', [CashAdvanceController::class, 'update'])->name('cash_advance.update');
// Route::post('/cash-advance/delete', [CashAdvanceController::class, 'destroy'])->name('cash_advance.delete');
// Route::get('/cash-advance/sdo', [CashAdvanceController::class, 'showSdo']);