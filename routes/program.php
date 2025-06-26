<?php

use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

    Route::view('/program', 'program')->name('program');
    Route::post('program', [ProgramController::class, 'store'])->name('program.store');
    Route::get('program/index', [ProgramController::class, 'index']);
    Route::post('program/update', [ProgramController::class, 'update'])->name('program.update');
    Route::delete('program/delete/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');

  