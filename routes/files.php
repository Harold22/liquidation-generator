<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FileDataController;
use Illuminate\Support\Facades\Route;


Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
Route::get('/files/index/{sdo}', [FileController::class, 'index']);
Route::get('/files/getSdoTotal/{sdo}', [FileController::class, 'getSdoTotal']);
Route::post('/files/delete/{id}', [FileController::class, 'destroy'])->name('file.delete');
Route::get('/files/rcd/{id}', [FileController::class, 'getIdToRCD']);
Route::get('/files/data/{fileIds}', [FileDataController::class, 'getData']);
Route::get('/files/list/{fileId}', [FileDataController::class, 'getIndividualList']);
Route::post('/data/delete/{id}', [FileDataController::class, 'destroy']);
Route::post('/data/update/', [FileDataController::class, 'update'])->name('beneficiary.update');
Route::post('/file/update/', [FileController::class, 'update'])->name('file.update');


