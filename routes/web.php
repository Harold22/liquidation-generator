<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('auth.login'))->name('login.view');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cash-advances/add', function () {
    return view('cash-advances.cash-advance');
})->middleware(['auth', 'verified'])->name('cash-advance.add');

Route::get('/cash-advances/list', function () {
    return view('cash-advances.cash-advance-list');
})->middleware(['auth', 'verified'])->name('cash-advance.list');

Route::get('/import-files', function () {
    return view('import-files.import-files');
})->middleware(['auth', 'verified'])->name('import-files');

Route::get('/rcd/{id}', function () {
    return view('rcd');
})->middleware(['auth', 'verified'])->name('rcd');

Route::get('/cdr/{id}', function () {
    return view('cdr');
})->middleware(['auth', 'verified'])->name('cdr');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    require __DIR__.'/cash_advance.php';
    require __DIR__.'/files.php';
    require __DIR__.'/refund.php';
});

require __DIR__.'/auth.php';

