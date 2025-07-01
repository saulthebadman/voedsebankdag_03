<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AllergieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Allergie routes
    Route::get('/allergieen', [AllergieController::class, 'index'])->name('allergieen.index');
    Route::get('/allergieen/filter', [AllergieController::class, 'filterByAllergie'])->name('allergieen.filter');
    Route::get('/allergieen/gezin/{gezin}', [AllergieController::class, 'showGezinDetails'])->name('allergieen.gezin-details');
    Route::get('/allergieen/persoon/{persoon}/allergie/{allergie}/edit', [AllergieController::class, 'editPersoonAllergie'])->name('allergieen.edit-persoon-allergie');
    Route::put('/allergieen/persoon/{persoon}/allergie/{allergie}', [AllergieController::class, 'updatePersoonAllergie'])->name('allergieen.update-persoon-allergie');
});

require __DIR__.'/auth.php';
