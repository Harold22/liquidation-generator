<?php

use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;


Route::post('/refund', [RefundController::class, 'store'])->name('refund.add');
Route::get('/refund/show/{id}', [RefundController::class, 'show']);
Route::post('/refund/delete/{id}', [RefundController::class, 'destroy'])->name('refund.delete');

