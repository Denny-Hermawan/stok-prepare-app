<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PrepareController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Items routes
    Route::resource('items', ItemController::class);

    // Prepare routes
    Route::get('/prepare', [PrepareController::class, 'index'])->name('prepare.index');
    Route::post('/prepare', [PrepareController::class, 'store'])->name('prepare.store');
    Route::post('/prepare/check-existing', [PrepareController::class, 'checkExisting'])->name('prepare.check');
    Route::post('/prepare/get-stok-awal', [PrepareController::class, 'getStokAwal'])->name('prepare.stok-awal');

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
