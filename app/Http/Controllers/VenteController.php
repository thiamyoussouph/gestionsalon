<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ventes.index');
    }

    // Ajoute un produit scanné à la session pour la vente en cours
    public function ajouterProduit(Request $request)
    {
        $codeBarres = $request->code_barres;
        $quantite = $request->quantite;

        $produit = Produit::where('code_barres', $codeBarres)->firstOrFail();

        $vente = session()->get('vente', []);

        if(isset($vente[$produit->id])) {
            $vente[$produit->id]['quantite'] += $quantite;
        } else {
            $vente[$produit->id] = [
                "nom" => $produit->nom,
                "prix" => $produit->prix,
                "quantite" => $quantite
            ];
        }

        session()->put('vente', $vente);

        return redirect()->route('ventes.index')->with('success', 'Produit ajouté à la vente.');
    }

    // Finalise la vente, met à jour l'inventaire, et génère le reçu
    public function finaliserVente(Request $request)
    {
        $produits = $request->input('produits');
        $total = 0;
    
        // Générer le numéro de facture
        $date = now(); // Utiliser Carbon pour gérer la date
        $numero_facture = 'FBTV' . $date->format('Ymd') . $date->format('H') . $date->format('i') . $date->format('s');
    
        $vente = new Vente();
        $vente->numero_facture = $numero_facture;
        $vente->nom_client = $request->input('nom_client');
        $vente->user_id = auth()->id();
        $vente->mode_paiement = $request->input('mode_paiement');
        $vente->reference_paiement = $request->input('reference_paiement');
        $vente->numero_transaction = $request->input('numero_transaction');
        $vente->montant_recu = $request->input('montantRecu', 0); // Utiliser 'input' avec une valeur par défaut
    
        try {
        DB::transaction(function () use ($vente, $produits, &$total) {
            $vente->save();
    
            foreach ($produits as $details) {
                $produit = Produit::find($details['id']);
                if ($produit && $details['quantite'] > 0) {
                    $quantite = $details['quantite'];
                    $prix = $details['prix'];

                    if ($produit->quantite < $quantite) {
                        throw new \Exception("Stock insuffisant pour le produit : {$produit->nom}");
                    }

                    $totalProduit = $quantite * $prix;

                    $vente->produits()->attach($produit->id, [
                        'quantite' => $quantite,
                        'prix' => $prix,
                        'total' => $totalProduit
                    ]);

                    $produit->quantite -= $quantite;
                    $produit->save();

                    $total += $totalProduit;
                }
            }
    
            // Mise à jour du total et du montant rendu après calcul du total
            $vente->total = $total;
            $vente->montant_rendu = $vente->montant_recu - $total;
            $vente->status = ($vente->montant_recu >= $total) ? 1 : 0;
            $vente->save();
        });
    
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Vente finalisée avec succès',
            'total' => $total,
            'venteId' => $vente->id
        ]);
    }
    
    public function getProduitParCodeBarres($codeBarres)
    {
        
        $produit = Produit::where('code_barres', $codeBarres)->first();

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        return response()->json([
            'id' => $produit->id,
            'nom' => $produit->nom,
            'prix' => $produit->prix,
            'code_barres' => $produit->code_barres
        ]);
    }

    public function afficherRecu($id)
    {
        $vente = Vente::with(['produits'])->find($id);  // Précharge les produits associés à la vente
    
        if (!$vente) {
            return redirect()->back()->with('error', 'Vente non trouvée');
        }
    
        // 'produits' comprend désormais les éléments de la pivot table 'detail_ventes'
        $details = $vente->produits;
    
        // Calculer le total à partir des détails si nécessaire
        $total = $details->reduce(function ($carry, $item) {
            return $carry + ($item->pivot->quantite * $item->pivot->prix);
        }, 0);
    
        return view('ventes.recu', [
            'vente' => $details,
            'vente_info' => $vente,
            'total' => $total,
        ]);
    }
    
}
