<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;

/*
Route::get('/', function () {
    return redirect('/positions/index');
}); */

Route::get('/', [PositionController::class, 'index']);


Route::prefix('positions')->group(function () {
    Route::get('/', [PositionController::class, 'index'])->name('positions.index'); // View all positions
    Route::get('/create', [PositionController::class, 'create'])->name('positions.create'); // Show form to create a new position
    Route::post('/', [PositionController::class, 'store'])->name('positions.store'); // Create a new position
    Route::get('/{id}', [PositionController::class, 'show'])->name('positions.show'); // View details of a specific position
    Route::get('/{id}/edit', [PositionController::class, 'edit'])->name('positions.edit'); // Show form to edit an existing position
    Route::put('/{id}', [PositionController::class, 'update'])->name('positions.update'); // Update a position
    Route::delete('/{id}', [PositionController::class, 'destroy'])->name('positions.destroy'); // Soft delete a position
    Route::get('/positions/search', [PositionController::class, 'search'])->name('positions.search'); // search 
    Route::put('/positions/restore/{id}', [PositionController::class, 'restore'])->name('positions.restore'); //restore position
    Route::put('/positions/restore/{id}', [PositionController::class, 'restorePosition'])->name('positions.restore');

});