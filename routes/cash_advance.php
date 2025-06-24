<?php

use App\Http\Controllers\CashAdvanceController;
use Illuminate\Support\Facades\Route;

Route::post('/cash-advance/store', [CashAdvanceController::class, 'store'])->name('cash_advance.store');
Route::get('/cash-advance/index', [CashAdvanceController::class, 'index']);
Route::post('/cash-advance/update', [CashAdvanceController::class, 'update'])->name('cash_advance.update');
Route::post('/cash-advance/delete', [CashAdvanceController::class, 'destroy'])->name('cash_advance.delete');