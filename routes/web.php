<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategorieController;

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

    // Produits
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('/produits/store', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('/produits/{id}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
    Route::put('/produits/{id}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{id}', [ProduitController::class, 'destroy'])->name('produits.destroy');
    Route::post('/update-quantity', [ProduitController::class, 'updateQuantity']);
    Route::get('code-barres/{id}', [ProduitController::class, 'etiquette'])->name('code-barres');

    // Catégories
    Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategorieController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('/categories/{categorie}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{categorie}', [CategorieController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');

    // Ventes
    Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
    Route::post('/ventes/ajouter-produit', [VenteController::class, 'ajouterProduit'])->name('ventes.ajouterProduit');
    Route::post('/ventes/finaliser', [VenteController::class, 'finaliserVente'])->name('ventes.finaliserVente');
    Route::get('/produits/par-code-barres/{codeBarres}', [VenteController::class, 'getProduitParCodeBarres'])->name('produits.parCodeBarres');
    Route::get('/ventes/recu/{id}', [VenteController::class, 'afficherRecu'])->name('ventes.recu');
});

require __DIR__.'/auth.php';
