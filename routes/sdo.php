<?php

use App\Http\Controllers\SDOController;
use Illuminate\Support\Facades\Route;

   // sdo routes
    Route::view('/sdo', 'sdo')->name('sdo');
    Route::post('/sdo', [SDOController::class, 'store'])->name('sdo.store');
    Route::get('/getSDO/index', [SDOController::class, 'index']);
    Route::post('/sdo/update', [SDOController::class, 'update'])->name('sdo.update');
    Route::delete('sdo/delete/{id}', [SDOController::class, 'destroy']);
    Route::get('sdo/get-cash-advances/{id}/{year}', [SDOController::class, 'getCashAdvances']);