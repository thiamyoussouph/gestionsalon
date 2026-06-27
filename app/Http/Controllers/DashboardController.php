<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Categorie;

class DashboardController extends Controller
{
    public function index()
    {
        $ventesAujourdhui = Vente::whereDate('created_at', today())->count();
        $totalAujourdhui  = Vente::whereDate('created_at', today())->sum('total');
        $totalProduits    = Produit::count();
        $totalCategories  = Categorie::count();
        $stockFaible      = Produit::where('quantite', '<=', 5)->where('quantite', '>', 0)->get();
        $rupture          = Produit::where('quantite', 0)->get();
        $dernieresVentes  = Vente::with('produits')->latest()->take(5)->get();

        return view('dashboard', compact(
            'ventesAujourdhui', 'totalAujourdhui',
            'totalProduits', 'totalCategories',
            'stockFaible', 'rupture', 'dernieresVentes'
        ));
    }
}
