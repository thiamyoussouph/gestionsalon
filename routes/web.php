<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('layouts.app');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits/store', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::post('/update-quantity', [ProduitController::class, 'updateQuantity']);
    Route::get('code-barres/{id}', [ProduitController::class, 'etiquette'])->name('code-barres');

    Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
    Route::post('/ventes/ajouter-produit', [VenteController::class, 'ajouterProduit'])->name('ventes.ajouterProduit');
    Route::post('/ventes/finaliser', [VenteController::class, 'finaliserVente'])->name('ventes.finaliserVente');
    Route::get('/produits/par-code-barres/{codeBarres}', [VenteController::class, 'getProduitParCodeBarres'])->name('produits.parCodeBarres');
    Route::get('/ventes/recu/{id}', [VenteController::class, 'afficherRecu'])->name('ventes.recu');
});

require __DIR__.'/auth.php';
