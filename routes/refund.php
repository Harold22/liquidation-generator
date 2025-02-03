<?php

use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;


Route::post('/refund', [RefundController::class, 'store'])->name('refund.add');
Route::get('/refund/show/{id}', [RefundController::class, 'show']);
// Route::get('/files/getSdoTotal/{sdo}', [FileController::class, 'getSdoTotal']);
// Route::post('/files/delete/{id}', [FileController::class, 'destroy'])->name('file.delete');
// Route::get('/files/rcd/{id}', [FileController::class, 'getIdToRCD']);
// Route::get('/files/data/{fileIds}', [FileDataController::class, 'getData']);

