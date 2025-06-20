<?php

use App\Http\Controllers\OfficeController;
use Illuminate\Support\Facades\Route;

   Route::view('/offices', 'offices')->name('offices');
    Route::get('/getOffices', [OfficeController::class, 'index']);
    Route::post('offices', [OfficeController::class, 'store'])->name('office.store');
    Route::post('offices/update', [OfficeController::class, 'update'])->name('office.update');
    Route::delete('/offices/delete/{id}', [OfficeController::class, 'destroy']);
    Route::get('/offices/list', [OfficeController::class, 'getOffice']);