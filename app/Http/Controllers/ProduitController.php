<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Milon\Barcode\DNS1D;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('categorie')->get();
        $user = Auth::user();
        return view('produits.index', compact('produits', 'user'));
    }

    public function etiquette($id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return redirect()->route('produits.index')->with('error', 'Produit non trouvé.');
        }
        return view('produits.etiquette', compact('produit'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'quantite'     => 'required|integer|min:0',
        ]);

        $produit = Produit::create([
            'nom'          => $request->nom,
            'prix'         => $request->prix,
            'categorie_id' => $request->categorie_id,
            'quantite'     => $request->quantite,
        ]);

        $codeBarres = $this->generateBarcodeNumber();
        $d = new DNS1D();
        $barcodeImage = $d->getBarcodePNG($codeBarres, 'C39');
        $barcodePath = 'barcodes/' . $produit->id . '.png';
        Storage::disk('public')->put($barcodePath, base64_decode($barcodeImage));

        $produit->update([
            'code_barres'        => $codeBarres,
            'barcode_image_path' => $barcodePath,
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'quantite'     => 'required|integer|min:0',
        ]);

        $produit = Produit::findOrFail($id);
        $produit->update($request->only(['nom', 'prix', 'categorie_id', 'quantite']));

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();
        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function updateQuantity(Request $request)
    {
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

    private function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000, 9999999999);
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }
        return $number;
    }

    private function barcodeNumberExists($number)
    {
        return Produit::where('code_barres', $number)->exists();
    }
}
