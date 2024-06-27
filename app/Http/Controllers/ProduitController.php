<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Milon\Barcode\DNS1D;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = Produit::all();
        $user = Auth::user(); // Récupère l'utilisateur connecté
   
        return view('produits.index', compact('produits', 'user'));
    }
    public function etiquette($id) {
        $produit = Produit::find($id);  // Récupère un seul produit, donc variable au singulier
    
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé.');
        }
        
        return view('produits.etiquette', compact('produit'));  // Passez 'produit' et non 'produits'
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('form' , compact('categories'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $produit = new Produit();
        $produit->nom = $request->nom;
        $produit->prix = $request->prix;
        $produit->categorie_id = $request->categorie_id;
        $produit->quantite = $request->quantite;
        $produit->save(); // Sauvegarde initiale pour obtenir un ID
    
        // Génération de la valeur du code-barres
        $codeBarres = $this->generateBarcodeNumber();
        $produit->code_barres = $codeBarres; // Stocker la valeur du code-barres
    
        // Génération de l'image du code-barres et sauvegarde du chemin
        $d = new DNS1D();
        $barcodeImage = $d->getBarcodePNG($codeBarres, 'C39');
        $barcodePath = 'barcodes/' . $produit->id . '.png';
        Storage::disk('public')->put($barcodePath, base64_decode($barcodeImage));
        $produit->barcode_image_path = $barcodePath; // Sauvegarde du chemin vers l'image
    
        $produit->save();
    
        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }
    
    /**
     * Generate a unique barcode number.
     */
    private function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000, 9999999999); // Générer un nombre aléatoire
        // Vérifier que le numéro n'est pas déjà utilisé
        if ($this->barcodeNumberExists($number)) {
            return generateBarcodeNumber();
        }
        return $number;
    }
    
    /**
     * Check if a barcode number exists.
     */
    private function barcodeNumberExists($number)
    {
        return Produit::where('code_barres', $number)->exists();
    }
    
  

    // Génération du code-barres
   

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        //
    }
    public function updateQuantity(Request $request) {
        $barcode = $request->input('barcode');
        $product = Produit::where('code_barres', $barcode)->first();

        if ($product) {
            if ($product->quantite > 0) {
                $product->quantite -= 1;
                $product->save();

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Quantité insuffisante']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé']);
        }
    }
}
