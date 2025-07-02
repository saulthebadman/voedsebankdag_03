<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AllergieController;
use App\Http\Controllers\GezinAllergieDetailController;
use App\Http\Controllers\PersoonAllergieController;
use App\Http\Controllers\AllergieStatistiekController;
use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\ProductController;
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
    
    // Allergie routes - volledige module
    Route::get('/allergieen', [AllergieController::class, 'index'])->name('allergieen.index');
    Route::get('/allergieen/gezin/{gezin}', [GezinAllergieDetailController::class, 'showGezinDetails'])->name('allergieen.gezin-details');
    Route::get('/allergieen/persoon/{persoon}/allergie/{allergie}/edit', [PersoonAllergieController::class, 'editPersoonAllergie'])->name('allergieen.edit-persoon-allergie');
    Route::put('/allergieen/persoon/{persoon}/allergie/{allergie}', [PersoonAllergieController::class, 'updatePersoonAllergie'])->name('allergieen.update-persoon-allergie');
    
    // API routes voor AJAX calls
    Route::get('/api/allergieen/statistieken', [AllergieStatistiekController::class, 'apiStatistieken'])->name('api.allergieen.statistieken');
    Route::get('/api/allergieen/populair/{limiet?}', [AllergieStatistiekController::class, 'getPopulaireAllergieen'])->name('api.allergieen.populair');
    
    // Leverancier routes - volledige module
    Route::get('/leveranciers', [LeverancierController::class, 'index'])->name('leveranciers.index');
    Route::get('/leveranciers/create', [LeverancierController::class, 'create'])->name('leveranciers.create');
    Route::post('/leveranciers', [LeverancierController::class, 'store'])->name('leveranciers.store');
    Route::get('/leveranciers/{leverancier}', [LeverancierController::class, 'show'])->name('leveranciers.show');
    Route::delete('/leveranciers/{leverancier}', [LeverancierController::class, 'destroy'])->name('leveranciers.destroy');
    Route::get('/leveranciers/{leverancier}/edit', [LeverancierController::class, 'edit'])->name('leveranciers.edit');
    Route::put('/leveranciers/{leverancier}', [LeverancierController::class, 'update'])->name('leveranciers.update');

    // Product routes
    Route::get('/leveranciers/{leverancier}/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/leveranciers/{leverancier}/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::put('/leveranciers/{leverancier}/products/{product}/inline', [ProductController::class, 'inlineUpdate'])->name('products.inlineUpdate');
});

require __DIR__.'/auth.php';
